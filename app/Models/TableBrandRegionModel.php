<?php

namespace App\Models;

use CodeIgniter\Model;

class TableBrandRegionModel extends Model
{
    public function getTableBrandRegion($area_name = false, $date_from = false, $date_to = false)
    {
        $builder = $this->db->table('report_product')
            ->join('store', 'store.store_id=report_product.store_id')
            ->join('store_area', 'store_area.area_id=store.area_id')
            ->join('product', 'product.product_id=report_product.product_id')
            ->join('product_brand', 'product_brand.brand_id=product.brand_id');

        $builder = $builder->select(
            'report_id, 
            store.store_id, 
            store_area.area_id, 
            area_name, 
            product.product_id,
            product_brand.brand_id,
            brand_name,
            sum(compliance)/count(*)*100 as nilai, 
            tanggal,'
        );

        if ($area_name != false) {
            $builder = $builder->whereIn("area_name", $area_name);
        }

        if ($date_from != false and $date_to != false) {
            $builder = $builder->where("tanggal >=", $date_from);
            $builder = $builder->where("tanggal <=", $date_to);
        }

        $builder = $builder->groupBy('brand_name, area_name');
        $query = $builder->get()->getResultArray();
        return $query;
    }
}
