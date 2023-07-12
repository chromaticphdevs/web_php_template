<?php 
    namespace Form;
    use Core\Form;

    load(['Form'], CORE);

    class CommonForm extends Form {

        public function __construct() {
            parent::__construct();

            $this->addSortSelect();
        }

        public function addSortSelect() {
            $this->add([
                'type' => 'select',
                'name' => 'sort',
                'options' => [
                    'label' => 'Sort',
                    'option_values' => []
                ],
                'class' => 'form-control',
                'attributes' => [
                    'id' => 'B_sort'
                ]
            ]);
        }
    }