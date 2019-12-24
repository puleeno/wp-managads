<?php

namespace Managads;

class Ad
{
    public $id, $name, $type, $data, $description, $category_id = null;

    public function __construct($id)
    {
        $this->setId($id);
        $this->getAdFromDb();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAdFromDb()
    {
        global $managads_query;
        try {
            $ad = $managads_query->getAd($this->id);

            // If the ad is not exists stop this process.
            if (!$ad) {
                return;
            }

            $dbMapToObject = array(
                'ID' => 'id',
                'name' => 'name',
                'ad_type' => 'type',
                'ad_data' => 'data',
                'description' => 'decription',
                'category_id' => 'category_id'
            );

            // Set ad from DB to Objects
            foreach ($ad as $key => $value) {
                if (isset($dbMapToObject[$key])) {
                    $objKey = $dbMapToObject[$key];
                    $this->$objKey = $value;
                }
            }
        } catch (\Exception $e) {
        }
    }

    public function getData()
    {
        return apply_filters(
            'managads_ad_data',
            $this->data,
            $this,
        );
    }

    public function getContent()
    {
        $pre = apply_filters('managads_pre_ad_content', null, $this);
        if ($pre) {
            return $pre;
        }

        if ($this->type === 'html') {
            $content = $this->getData();
        }

        return apply_filters('managads_ad_content', $content, $this);
    }
}
