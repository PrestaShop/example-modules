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

namespace PrestaShop\Module\ExampleModuleMailtheme\MailTemplate\Transformation;

use PrestaShop\PrestaShop\Core\MailTemplate\MailTemplateInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\Transformation\AbstractTransformation;
use Symfony\Component\DomCrawler\Crawler;
use DOMElement;

/**
 * Class CustomMessageColorTransformation adds the custom color to all spans
 * with class subtitle.
 */
class CustomMessageColorTransformation extends AbstractTransformation
{
    /** @var string */
    private $customColor;

    /**
     * @param string $customColor
     *
     * @throws \PrestaShop\PrestaShop\Core\Exception\InvalidArgumentException
     */
    public function __construct($customColor)
    {
        parent::__construct(MailTemplateInterface::HTML_TYPE);
        $this->customColor = $customColor;
    }

    /**
     * {@inheritdoc}
     */
    public function apply($templateContent, array $templateVariables)
    {
        $crawler = new Crawler($templateContent);
        $customSpans = $crawler->filter('span[class="subtitle"]');
        /** @var DOMElement $customSpan */
        foreach ($customSpans as $customSpan) {
            $customSpan->setAttribute('style', sprintf('color: %s;', $this->customColor));
        }

        return $crawler->html();
    }
}
