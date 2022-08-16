<?php

declare(strict_types=1);

namespace Akeneo\Connector\Ui\Component\JobListing\Column;

use Akeneo\Connector\Api\Data\JobInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * @author    Agence Dn'D <contact@dnd.fr>
 * @copyright 2004-present Agence Dn'D
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.dnd.fr/
 */
class ActionsColumn extends Column
{
    private UrlInterface $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Description prepareDataSource function
     *
     * @param array[] $dataSource
     *
     * @return array[]
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    /** @var string $scheduleJobPath */
                    $scheduleJobPath = $this->getData('config/scheduleJobPath') ?: '#';
                    $scheduleJobUrl =  $this->urlBuilder->getUrl($scheduleJobPath,['entity_id' => $item['entity_id']]);
                    /** @var string $viewJobLogPath */
                    $viewJobLogPath = $this->getData('config/viewJobLogPath') ?: '#';
                    $viewJobLogPathFilter = base64_encode(JobInterface::CODE . '=' . $item['code']);
                    $viewJobLogUrl =  $this->urlBuilder->getUrl($viewJobLogPath, ['filter' => $viewJobLogPathFilter]);

                    $html = '<a href="' . $scheduleJobUrl . '">' . __('Schedule Job') . '</a> / ';
                    $html .= '<a href="' . $viewJobLogUrl . '">' . __('View Logs') . '</a>';
                    $item['actions'] = $html;
                }
            }
        }

        return $dataSource;
    }

}
