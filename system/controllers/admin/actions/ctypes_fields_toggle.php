<?php

class actionAdminCtypesFieldsToggle extends cmsAction {

    public function run($mode, $ctype_id, $field_id){

        if (!in_array($mode, array('list', 'item')) || !$ctype_id || !$field_id) {
			return $this->cms_template->renderJSON(array(
				'error' => true
			));
		}

        $ctype = $this->model_content->getContentType($ctype_id);
		$field = $this->model_content->getContentField($ctype['name'], $field_id);

		$visibility_field = $mode=='list' ? 'is_in_list' : 'is_in_item';

		$is_visible = $field[$visibility_field] ? 0 : 1;

		$this->model_content->toggleContentFieldVisibility($ctype['name'], $field_id, $visibility_field, $is_visible);

        if($is_visible && !empty($field['options']['context_list']) && array_search('0', $field['options']['context_list']) === false){
            $is_visible = -1;
        }

		return $this->cms_template->renderJSON(array(
			'error' => false,
			'is_on' => $is_visible
		));

    }

}
