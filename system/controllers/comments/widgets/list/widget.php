<?php
class widgetCommentsList extends cmsWidget {

    public $is_cacheable = false;

    public function run(){

        $controller_options = cmsController::loadOptions('comments');

        if(!empty($controller_options['disable_icms_comments'])){
            return false;
        }

        $show_avatars = $this->getOption('show_avatars', true);
        $show_text    = $this->getOption('show_text', false);
        $limit        = $this->getOption('limit', 10);

        $model = cmsCore::getModel('comments');

        $model->orderBy('date_pub', 'desc');

        if (!cmsUser::isAllowed('comments', 'view_all')) {
            $model->filterEqual('is_private', 0);
        }

        cmsEventsManager::hook('comments_list_filter', $model);

        $items = $model->filterIsNull('is_deleted')->limit($limit)->getComments();
        if (!$items) { return false; }

        $items = cmsEventsManager::hook('comments_before_list', $items);

        return array(
            'show_avatars' => $show_avatars,
            'show_text'    => $show_text,
            'items'        => $items
        );

    }

}
