<?php
function uploadFile($FILES, $path)
{
    $CI = &get_instance();
    if (isset($FILES['file']["name"])) {
        $fileName = $FILES['file']['name'];
        $override = 'false';
        $upload_ready = false;
        $dirName = $path;

        if (!file_exists($dirName)) {
            mkdir($dirName, 0755, true);
        }

        if (file_exists($dirName . '/' . $fileName)) {
            $override = 'true';
            if ($override == 'true') {
                $fileName = date("Ymd_His") . $fileName;
                $insertData['Document_path'] = $dirName . '/' .  $fileName;
                $upload_ready = true;
            }
        } else {
            $upload_ready = true;
        }

        if ($upload_ready) {
            $config['upload_path'] = $dirName;
            $config['allowed_types'] = 'jpeg|jpg|png';
            $config['max_size'] = '100000';
            $config['file_name'] = $fileName;
            $config['remove_spaces'] = FALSE;
            $CI->load->library('upload', $config);
            if (!$CI->upload->do_upload('file')) {
                return array("error",  $CI->upload->display_errors());
            } else {
                $data = $CI->upload->data();
                return array("success",  $fileName);
            }
        }
    } else {
        return array("error",  "Insufficient Information sent.");
    }
}
