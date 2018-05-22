<?php

namespace App;

use App\Models\Translation;

trait Translator
{

    protected $modelTarget;

    public function model()
    {
        return new Translation;
    }

    public function saveCreate($data)
    {
        foreach (config('creeper.language') as $key => $item) {
            if ($key == 0) {
                $data_single = $data;
                foreach ($data_single['language'][$item['code']] as $key => $value) {
                    $data_single[$key] = $value;
                }
                $data_single = $this->modelTarget->fill($data_single)->getAttributes();
                $data_single = $this->modelTarget->create($data_single);
            } else {
                foreach ($data['language'][$item['code']] as $key => $value) {
                    $translation['table_name'] = $this->modelTarget->getTable();
                    $translation['column_name'] = $key;
                    $translation['foreign_key'] = $data_single->id;
                    $translation['locale'] = $item['code'];
                    $translation['value'] = $value;
                    $this->model()->create($translation);
                }
            }
        }
    }

    public function saveEdit($id, $data)
    {
        $this->model()->where('foreign_key', $id)
            ->where('table_name', $this->modelTarget->getTable())
            ->delete();
        foreach (config('creeper.language') as $key => $item) {
            if ($key == 0) {
                $data_single = $data;
                foreach ($data_single['language'][$item['code']] as $key => $value) {
                    $data_single[$key] = $value;
                }
                $data_single = $this->modelTarget->fill($data_single)->getAttributes();
                $this->modelTarget->find($id)->update($data_single);
            } else {
                foreach ($data['language'][$item['code']] as $key => $value) {
                    $translation['table_name'] = $this->modelTarget->getTable();
                    $translation['column_name'] = $key;
                    $translation['foreign_key'] = $id;
                    $translation['locale'] = $item['code'];
                    $translation['value'] = $value;
                    $this->model()->create($translation);
                }
            }
        }
    }

    public function dataLanguage($data)
    {
        $merge_data[config('creeper.language')[0]['code']] = $this->modelTarget->fill($data->getAttributes())->getAttributes();
        if (count(config('creeper.language')) > 1) {
            $translations = $this->model()->where('foreign_key', $data->id)
                ->where('table_name', $this->modelTarget->getTable())
                ->get();
            if ($translations) {
                foreach ($translations as $key => $value) {
                    $merge_data[$value->locale][$value->column_name] = $value->value;
                }
            }
        }
        $data['language'] = $merge_data;
        return $data;
    }

    public function deleteData($id)
    {
        $this->modelTarget->find($id)->delete();
        $this->model()->where('foreign_key', $id)
            ->where('table_name', $this->modelTarget->getTable())
            ->delete();
    }

    public function language($field)
    {
        $locale = session()->has('locale') ? session()->get('locale') : config('creeper.language_default');
        if ($locale == config('creeper.language_default')) {
            return $this->$field;
        } else {
            $data = $this->model()->where('foreign_key', $this->id)
                ->where('table_name', $this->table)
                ->where('locale', $locale)
                ->where('column_name', $field)
                ->first();
            if ($data) {
                return $data->value;
            }
        }
    }
}