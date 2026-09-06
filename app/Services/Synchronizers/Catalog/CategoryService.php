<?php

namespace App\Services\Synchronizers\Catalog;

use App\Models\ProductCategory;

class CategoryService
{
    public static function save($categories, $parentId = null) {
        $syncCategories = [];
        foreach ($categories as $categoryArray) {
            $language = key($categoryArray['name']);
            $productCategory = ProductCategory::query()
                ->where("name->{$language}", $categoryArray['name'][$language])
                ->where('parent_id', $parentId);
            foreach (languages() as $code => $language) {
                if (isset($categoryArray['name'][$code]) && $categoryArray['name'][$code]) {
                    $productCategory = $productCategory->orWhere(function ($query) use ($categoryArray, $parentId, $code) {
                        $query->where("name->{$code}", $categoryArray['name'][$code])->where('parent_id', $parentId);
                    });
                }
            }
            $productCategory = $productCategory->first();

            if (! $productCategory) {
                if (! $parentId) {
                    $order = ProductCategory::whereNull('parent_id')->max('order') ?? 0;
                    $order += 1;
                } else {
                    $order = ProductCategory::where('parent_id', $parentId)->max('order') ?? 0;
                    $order += 1;
                }
                $productCategory = activity()->withoutLogs(function () use ($categoryArray, $parentId, $order) {
                    $productCategory = ProductCategory::create([
                        'status' => true,
                        'name' => $categoryArray['name'],
                        'description' => $categoryArray['description'],
                        'external' => $categoryArray['external'],
                        'include_in_menu' => true,
                        'order' => $order,
                        'parent_id' => $parentId,
                    ]);

                    return $productCategory;
                });
            }
            $syncCategories[] = $productCategory->id;
            if (isset($categoryArray['children'])) {
                $syncCategories = array_merge($syncCategories, self::save($categoryArray['children'], $productCategory->id));
            }
        }

        return $syncCategories;
    }
}
