<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 后台权限API
 * @author royalwang
 *
 */
class groupbuy_merchant_purview_api extends Component_Event_Api {
    
    public function call(&$options) {
        $purviews = array(
            array('action_name' => __('团购管理'), 'action_code' => 'groupbuy_manage', 'relevance'   => ''),
        	array('action_name' => __('团购添加'), 'action_code' => 'groupbuy_add', 'relevance'   => ''),
        	array('action_name' => __('团购更新'), 'action_code' => 'groupbuy_update', 'relevance'   => ''),
        	array('action_name' => __('团购删除'), 'action_code' => 'groupbuy_delete', 'relevance'   => '')
        );
        
        return $purviews;
    }
}

// end