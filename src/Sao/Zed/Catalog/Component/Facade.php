<?php
use Sao\Zed\Catalog\Component\Exporter\KeyValue\Artwork as KeyValueArtwork;
use Sao\Zed\Catalog\Component\Exporter\Solr\Artwork as SolrArtwork;

/**
 * @property Generated_Zed_Catalog_Component_Factory $factory
 */
class Sao_Zed_Catalog_Component_Facade extends ProjectA_Zed_Catalog_Component_Facade
{

    /**
     * read catalog setup from db and print
     * @return string
     */
    public function reverseInstall()
    {
        return $this->factory->getInternalReverseInstall()->reverseInstall();
    }

    /**
     * @return KeyValueArtwork
     */
    public function getExporterKeyValueArtwork()
    {
        return $this->factory->getExporterKeyValueArtwork();
    }

    /**
     * @return SolrArtwork
     */
    public function getExporterSolrArtwork()
    {
        return $this->factory->getExporterSolrArtwork();
    }

    /**
     * @fixme @todo @hopefully soon deprecated ;)
     * @param string $sku
     * @return string
     */
    public function getProductType($sku)
    {
        //TODO remove mock
        // FIXME FIXME FIXME
        if (substr($sku, 0, 3) == 'P1-') {
            return 'marketplace';
        } else {
            return 'manufactured';
        }
    }
}
