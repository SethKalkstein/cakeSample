<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;


class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isNew() && !$entity->slug){
            $sluggedTitle = Text::slug($entity->title);
            //trim slug to maximum length allowed by schema
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    public function validationDefault(Validator $validator)
    {
        $validator
        ->allowEmptyString("title", false)
        ->minLength("title", 10)
        ->maxLength("title", 225)

        ->allowEmptyString("body", false)
        ->minLength("body", 10);

        return $validator;
    }
}

