<?php
/**
 * 2007-2019 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
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
