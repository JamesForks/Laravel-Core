<?php namespace GrahamCampbell\Core\Providers\Common;

/**
 * This file is part of Laravel Core by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package    Laravel-Core
 * @author     Graham Campbell
 * @license    Apache License
 * @copyright  Copyright 2013 Graham Campbell
 * @link       https://github.com/GrahamCampbell/Laravel-Core
 */

trait TraitPaginateProvider {

    /**
     * The paginated links generated by the paginate method.
     *
     * @var string
     */
    protected $paginatelinks;

    /**
     * Get a paginated list of the models.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function paginate() {
        $model = $this->model;

        if (property_exists($model, 'order')) {
            $values = $model::orderBy($model::$order, $model::$sort)->paginate($model::$paginate, $model::$index);
        } else {
            $values = $model::paginate($model::$paginate, $model::$index);
        }

        if (count($values) != 0) {
            $this->paginatelinks = $values->links();
        } else {
            $this->paginatelinks = '';
        }

        return $values;
    }

    /**
     * Get the paginated links.
     *
     * @return string
     */
    public function links() {
        return $this->paginatelinks;
    }
}
