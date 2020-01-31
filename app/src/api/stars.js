import axios from "axios";

export default {
    setRating(adminAjaxUrl, ajaxConfig, { currentRating, postId, widgetId }){
        postId = parseInt(postId);
        if(!postId || !adminAjaxUrl || !currentRating){
            throw new Error('Invalid Post ID');
        }
        let formData = new FormData();
        formData.append("action", "vw_post_rating_update");
        formData.append("rating", currentRating);
        formData.append("post_id", postId);
        formData.append("widget_id", widgetId);
        return axios.post(adminAjaxUrl, formData, ajaxConfig)
    }
}