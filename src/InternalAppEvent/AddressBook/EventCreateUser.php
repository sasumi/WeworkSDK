<?php

namespace LFPhp\WeworkSdk\InternalAppEvent\AddressBook;

class EventCreateUser extends EventChangeContact {
	public $user_id;
	public $name;
	public $department_id_list = [];
	public $as_leader_department_id_list = [];
	public $mobile;
	public $position;
	public $gender;
	public $email;
	public $avatar;
	public $alias;
	public $telephone;
	public $ext_attr;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->user_id = $this->getValueByTagName('UserID');
		$this->name = $this->getValueByTagName('Name');
		$this->department_id_list = explode(',', $this->getValueByTagName('Department'));
		$is_leader_in_department = explode(',', $this->getValueByTagName('IsLeaderInDept'));

		if($this->department_id_list && $is_leader_in_department){
			foreach($is_leader_in_department as $k => $is){
				if($is){
					$this->as_leader_department_id_list[] = $this->department_id_list[$k];
				}
			}
		}

		$this->mobile = $this->getValueByTagName('Mobile');
		$this->position = $this->getValueByTagName('Position');
		$this->gender = $this->getValueByTagName('Gender');
		$this->email = $this->getValueByTagName('Email');
		$this->avatar = $this->getValueByTagName('Avatar');
		$this->alias = $this->getValueByTagName('Alias');
		$this->telephone = $this->getValueByTagName('Telephone');
	}
}
