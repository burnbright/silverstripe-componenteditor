<?php

class EditComponentForm extends Form{

	protected $object;
	
	public function __construct($controller, $name, DataObjectInterface $object) {
		$this->object = $object;
		$fields = $object->getFrontEndFields(array(
			get_class($object) => $object
		));
		$fields->push(new HiddenField("ID","ID"));
		$actions = new FieldList(
			new FormAction("save", "Save ".$object->i18n_singular_name())
		);
		parent::__construct($controller, $name, $fields, $actions);
		$object->extend('updateEditComponentForm', $this);

		if($this->object->isInDB()){
			$this->loadDataFrom($this->object);
		}

		//all fields are required
		if(!$this->validator){
			$this->setValidator(new RequiredFields(
				array_keys($fields->saveableFields())
			));
		}
	}

	public function save($data, $form) {
		$member = Member::currentUser();
		$isnew = !$this->object->isInDB();
		if(
			($isnew && $this->object->canCreate($member)) ||
			(!$isnew && $this->object->canEdit($member))
		){
			$form->saveInto($this->object);
			$this->object->write();
			if($isnew){
				$form->sessionMessage($this->object->i18n_singular_name()." has been created.", "good");
			}else{
				$form->sessionMessage($this->object->i18n_singular_name()." has been updated.", "good");
			}
		}

		return $this->controller->redirectBack();
	}

}
