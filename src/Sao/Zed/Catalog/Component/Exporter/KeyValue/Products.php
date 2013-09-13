<?php
namespace Sao\Zed\Catalog\Component\Exporter\KeyValue;

use ProjectA\Zed\Catalog\Component\Exporter\Products as CoreProducts;
use ProjectA\Zed\Catalog\Component\Exporter\QueryBuilder\AbstractProduct;
use ProjectA\Zed\Yves\Component\Model\Export\AbstractExport;

/**
 * Class Products
 * @package Sao\Zed\Catalog\Component\Exporter\KeyValue
 */
abstract class Products extends CoreProducts implements
     \ProjectA_Zed_Yves_Component_Interface_Exporter_Memcache,
     \Sao_Shared_Catalog_Interface_ProductAttributeConstant,
     \Sao_Shared_Catalog_Interface_ProductAttributeSetConstant,
     \Sao_Shared_Library_StorageKeyConstant,
     \ProjectA_Zed_Yves_Component_Dependency_Facade_Interface
{

    use \ProjectA_Zed_Yves_Component_Dependency_Facade_Trait;

    /**
     * @return string
     */
    abstract protected function getProductAttributeSetName();

    /**
     * @return AbstractProduct
     */
    abstract protected function getProductQueryBuilder();

    /**
     * @param array $product
     * @return array
     */
    abstract protected function transformProductToData($product);

    /**
     * @return string
     */
    public function getName()
    {
        return 'Products ' . $this->getProductAttributeSetName();
    }

    /**
     * @return \ProjectA_Zed_Yves_Component_Exporter_Task_Entity
     */
    public function getExportTask()
    {
        $task = $this->facadeYves->getExporterTaskRaw();
        $task->setLazyCollection(
            new \ProjectA_Zed_Library_Propel_RawLazyCollection($this->getProductQueryBuilder())
        );
        return $task;
    }

    /**
     * @param \Traversable $collection
     * @param AbstractExport $exportModel
     * @param \ArrayIterator $reporter
     */
    public function exportData(
        \Traversable $collection,
        AbstractExport $exportModel,
        \ArrayIterator $reporter
    ) {
        $facade = $this->factory->getFacade();
        $reportName = $this->getName() . ' exported';
        $reporter[$reportName] = 0;

        foreach ($collection as $product) {

            $data = array();
            $pairProductData = $this->transformProductToData($product);

            $productKey = \ProjectA_Shared_Library_Storage::getProductKey($product['sku']);
            $data[$productKey] = $pairProductData;

            if (isset($product[self::ATTRIBUTE_URL])) {
                $productUrlKey = \ProjectA_Shared_Library_Storage::getProductUrlKey($product[self::ATTRIBUTE_URL]);
                $data[$productUrlKey] = $product['sku'];
            }

            $exportModel->write($data);
            $reporter[$reportName]++;
        }
    }
}
