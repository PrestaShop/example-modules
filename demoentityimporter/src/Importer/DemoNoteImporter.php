<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoEntityImporter\Importer;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\DemoEntityImporter\Entity\DemoNote;
use PrestaShop\PrestaShop\Core\Import\Engine\EntityImporter\AbstractEntityImporter;
use PrestaShop\PrestaShop\Core\Import\Engine\EntityImporter\RowMapper;
use PrestaShop\PrestaShop\Core\Import\Engine\ImportJobContext;
use PrestaShop\PrestaShop\Core\Import\Engine\ImportMessage;
use PrestaShop\PrestaShop\Core\Import\Engine\ImportPhaseDefinition;
use PrestaShop\PrestaShop\Core\Import\Engine\PhaseBatchResult;
use PrestaShop\PrestaShop\Core\Import\EntityField\EntityField;
use PrestaShop\PrestaShop\Core\Import\EntityField\EntityFieldCollection;
use PrestaShop\PrestaShop\Core\Import\EntityField\EntityFieldCollectionInterface;
use PrestaShop\PrestaShop\Core\Import\File\ResumableFileReaderInterface;
use Throwable;

/**
 * A complete module importer: it validates the file in a pausing
 * 'validation' phase, then persists one DemoNote Doctrine entity per row in
 * the 'database' phase (create, or update when an id column matches an
 * existing note).
 *
 * Because the service is autoconfigured (see config/services.yml), the core
 * automatically tags it and it appears in the EntityImporterRegistry next to
 * the core importers: the import page entity dropdown, the mapping screen
 * and the batch execution all pick it up with no extra wiring.
 *
 * The heavy lifting comes from the core:
 * - extending AbstractEntityImporter provides the cursor-resumable batch
 *   loop (iterateBatch), the phase-id guard and the default unit count;
 * - ResumableFileReaderInterface and RowMapper are core services injected
 *   as-is.
 *
 * Note for importers that build a PhaseBatchResult themselves instead of
 * returning iterateBatch(): all four constructor arguments are required, and a
 * batch that consumed nothing must hand back the cursor it was given
 * ($context->getResumeCursor()). Returning a fresh result without it would
 * rewind the reader to the start of the file while the phase offset stayed put,
 * re-importing rows under the wrong indexes.
 *
 * See PrestaShop\PrestaShop\Core\Import\Engine\EntityImporter\ProductImporter
 * in the core for the full-scale reference implementation.
 */
class DemoNoteImporter extends AbstractEntityImporter
{
    public const ENTITY_TYPE = 'demo_note';

    protected const NOTE_MAX_LENGTH = 255;

    public function __construct(
        ResumableFileReaderInterface $fileReader,
        RowMapper $rowMapper,
        protected readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct($fileReader, $rowMapper);
    }

    public function getEntityType(): string
    {
        return self::ENTITY_TYPE;
    }

    public function getLabel(): string
    {
        return 'Demo notes';
    }

    public function getFields(): EntityFieldCollectionInterface
    {
        // no field is flagged "required": that EntityField flag is deprecated
        // and never enforced anything server-side. Required values are checked
        // PER ROW instead, in the validation phase below, because a value is
        // only mandatory when the row CREATES an entity and one file can create
        // and update
        return EntityFieldCollection::createFromArray([
            new EntityField('id', 'ID'),
            new EntityField('note', 'Note'),
        ]);
    }

    public function getPhases(): array
    {
        return [
            new ImportPhaseDefinition(ImportPhaseDefinition::PHASE_VALIDATION, 'Validating notes', true),
            new ImportPhaseDefinition(ImportPhaseDefinition::PHASE_DATABASE, 'Importing notes'),
        ];
    }

    public function processPhaseBatch(string $phaseId, ImportJobContext $context, int $limit): PhaseBatchResult
    {
        $this->assertKnownPhase($phaseId);

        if (ImportPhaseDefinition::PHASE_VALIDATION === $phaseId) {
            return $this->iterateBatch($context, $limit, function (array $row, int $rowIndex): array {
                $messages = [];
                $note = $row['note'] ?? '';
                if ('' === $note) {
                    $messages[] = $this->message(ImportMessage::SEVERITY_ERROR, ImportPhaseDefinition::PHASE_VALIDATION, 'The note text is required.', $rowIndex, 'note');
                } elseif (mb_strlen($note) > self::NOTE_MAX_LENGTH) {
                    $messages[] = $this->message(ImportMessage::SEVERITY_ERROR, ImportPhaseDefinition::PHASE_VALIDATION, sprintf('The note exceeds %d characters.', self::NOTE_MAX_LENGTH), $rowIndex, 'note');
                }

                // an error marks the row as skipped for the later phases
                return ['messages' => $messages, 'skipped' => $this->containsError($messages)];
            });
        }

        return $this->iterateBatch($context, $limit, function (array $row, int $rowIndex) use ($context): array {
            if ($context->isRowSkipped($rowIndex)) {
                return ['messages' => [], 'skipped' => false];
            }

            try {
                $this->importRow($row);
            } catch (Throwable $e) {
                // a failing row must fail THIS ROW only, never the batch
                return [
                    'messages' => [$this->message(ImportMessage::SEVERITY_ERROR, ImportPhaseDefinition::PHASE_DATABASE, sprintf('The note could not be saved: %s', $e->getMessage()), $rowIndex)],
                    'skipped' => true,
                ];
            }

            return ['messages' => [], 'skipped' => false];
        });
    }

    /**
     * @param array<string, string> $row mapped row values
     */
    protected function importRow(array $row): void
    {
        $note = null;
        $id = $row['id'] ?? '';
        if (ctype_digit($id)) {
            $note = $this->entityManager->find(DemoNote::class, (int) $id);
        }

        if (null !== $note) {
            $note->setNote($row['note']);
        } else {
            $note = new DemoNote($row['note']);
            $this->entityManager->persist($note);
        }

        $this->entityManager->flush();
    }

    /**
     * Two conventions are worth copying from here.
     *
     * ImportMessage carries a cumulative LIST of row indexes, not a single
     * row: the engine coalesces messages that are equal on everything but
     * their rows, so one report line can name every row it applies to. A
     * per-row message therefore passes a one-element list, and a file-level
     * message passes none at all.
     *
     * The field is what the back office highlights in the report, so it names
     * the column the merchant has to go and fix. A failure that belongs to the
     * row rather than to any one column — the catch-all below, where any
     * command may have thrown — passes null instead of blaming a column that
     * may be perfectly valid.
     */
    protected function message(string $severity, string $phase, string $text, int $rowIndex, ?string $field = null): ImportMessage
    {
        return new ImportMessage($severity, $phase, $text, [$rowIndex], $field);
    }
}
