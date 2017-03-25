<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB as D;

class VirtualTableGenController extends Controller
{
   public function generateTable($org_name)
    {

     $org_database =[
        [
          'tab_name'=>$org_name.'_datasets',
          'columns'=>[
          "`dataset_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
          "`dataset_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'file path'",
          "`dataset_file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'custom name'",
          "`dataset_records` longtext COLLATE utf8_unicode_ci",
          "`dataset_table` varchar(255) COLLATE utf8_unicode_ci NOT NULL",
          "`user_id` int(10) UNSIGNED NOT NULL",
          "`uploaded_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
          "`created_at` timestamp NULL DEFAULT NULL",
          "`updated_at` timestamp NULL DEFAULT NULL",
          "`dataset_columns` text COLLATE utf8_unicode_ci",
          "`validated` tinyint(1) NOT NULL DEFAULT '0'"
          ]
        ],
        [
          'tab_name'=>$org_name.'_surveys',
          'columns'=>[
        "`survey_table` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
        "`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL",
        "`created_by` int(11) NOT NULL",
        "`description` text COLLATE utf8_unicode_ci",
        "`status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'",
        "`created_at` timestamp NULL DEFAULT NULL",
        "`updated_at` timestamp NULL DEFAULT NULL",
        "`deleted_at` timestamp NULL DEFAULT NULL"
          ]
        ],
        [
          'tab_name'=>$org_name.'_survey_questions',
          'columns'=>[
          "`survey_id` int(10) UNSIGNED NOT NULL",
          "`answer` text COLLATE utf8_unicode_ci NOT NULL",
          "`question` varchar(255) COLLATE utf8_unicode_ci NOT NULL",
          "`group_id` int(10) UNSIGNED NOT NULL",
          "`created_at` timestamp NULL DEFAULT NULL",
          "`updated_at` timestamp NULL DEFAULT NULL",
          "`deleted_at` timestamp NULL DEFAULT NULL"
          ]
        ],
        [
          'tab_name'=>$org_name.'_survey_question_groups',
          'columns'=>[
            "`survey_id` int(10) UNSIGNED NOT NULL",
            "`title` varchar(255) COLLATE utf8_unicode_ci NOT NULL",
            "`description` text COLLATE utf8_unicode_ci",
            "`created_at` timestamp NULL DEFAULT NULL",
            "`updated_at` timestamp NULL DEFAULT NULL",
            "`deleted_at` timestamp NULL DEFAULT NULL"
          ]
        ], 

        [
          'tab_name'=>$org_name.'_generated_visuals',
          'columns'=>[
            "`visual_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL",
            "`dataset_id` int(11) NOT NULL",
            "`columns` text COLLATE utf8_unicode_ci",
            "`query_result` longtext COLLATE utf8_unicode_ci",
            "`visual_settings` longtext COLLATE utf8_unicode_ci",
            "`filter_counts` longtext COLLATE utf8_unicode_ci",
            "`filter_columns` text COLLATE utf8_unicode_ci",
            "`chart_type` text COLLATE utf8_unicode_ci",
            "`created_by` int(10) UNSIGNED NOT NULL",
            "`created_at` timestamp NULL DEFAULT NULL",
            "`updated_at` timestamp NULL DEFAULT NULL"
          ]
        ], 
        [
          'tab_name'=>$org_name.'_generated_visual_queries',
          'columns'=>[
            "`visual_id` int(10) UNSIGNED NOT NULL",
            "`query` text COLLATE utf8_unicode_ci",
            "`query_result` longtext COLLATE utf8_unicode_ci",
            "`created_by` int(10) UNSIGNED NOT NULL",
            "`created_at` timestamp NULL DEFAULT NULL",
            "`updated_at` timestamp NULL DEFAULT NULL"
          ]
        ], 
        [
          'tab_name'=>$org_name.'_maps',
          'columns'=>[
            "`code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
            "`code_albha_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
            "`code_albha_3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
            "`code_numeric` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
            "`parent` int(11) DEFAULT NULL",
            "`title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
            "`description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
            "`map_data` longtext COLLATE utf8_unicode_ci",
            "`status` enum('disable','enable') COLLATE utf8_unicode_ci NOT NULL",
            "`created_at` timestamp NULL DEFAULT NULL",
            "`updated_at` timestamp NULL DEFAULT NULL"
          ]
        ], 
        [
          'tab_name'=>$org_name.'_visualisations',
          'columns'=>[
            "`dataset_id` int(10) UNSIGNED NOT NULL",
            "`visual_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL",
            "`settings` text COLLATE utf8_unicode_ci",
            "`options` text COLLATE utf8_unicode_ci",
            "`created_by` int(10) UNSIGNED NOT NULL",
            "`deleted_at` timestamp NULL DEFAULT NULL",
            "`created_at` timestamp NULL DEFAULT NULL",
            "`updated_at` timestamp NULL DEFAULT NULL"
          ]
        ],
        [
          'tab_name'=>$org_name.'_user_roles',
          'columns'=>[
            "`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL",
            "`description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
            "`deleted_at` timestamp NULL DEFAULT NULL",
            "`created_at` timestamp NULL DEFAULT NULL",
            "`updated_at` timestamp NULL DEFAULT NULL"
          ]
        ],
        [
          'tab_name'=>$org_name.'_userpages',
          'columns'=>[
	          "`page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL",
			  "`page_subtitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
			  "`page_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL",
			  "`content` text COLLATE utf8_unicode_ci NOT NULL",
			  "`page_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL",
			  "`status` int(11) NOT NULL",
			  "`created_by` int(10) UNSIGNED NOT NULL",
			  "`deleted_at` timestamp NULL DEFAULT NULL",
			  "`created_at` timestamp NULL DEFAULT NULL",
			  "`updated_at` timestamp NULL DEFAULT NULL"
          ]
        ],
        [
          'tab_name'=>$org_name.'_survey_settings',
          'columns'=>[
          "`key` varchar(255) COLLATE utf8_unicode_ci NOT NULL",
        "`value` text COLLATE utf8_unicode_ci DEFAULT NULL",
        "`survey_id` int(11) NOT NULL",
        "`deleted_at` timestamp NULL DEFAULT NULL",
        "`created_at` timestamp NULL DEFAULT NULL",
        "`updated_at` timestamp NULL DEFAULT NULL"
          ]
        ]

      ];


      foreach ($org_database as $key => $value) {
        D::select("CREATE TABLE `{$value['tab_name']}` ( " . implode(', ', $value['columns']) . " ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        D::select("ALTER TABLE `{$value['tab_name']}` ADD `id` INT(100) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Row ID' FIRST");
      }

    return true;
    }
}
