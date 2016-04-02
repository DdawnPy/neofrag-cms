<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_news extends Module
{
	public $title       = '{lang news}';
	public $description = '';
	public $icon        = 'fa-file-text-o';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $routes      = array(
		//Index
		'{page}'                                   => 'index',
		'{id}/{url_title}'                         => '_news',
		'tag/{url_title}{pages}'                   => '_tag',
		'category/{id}/{url_title}{pages}'         => '_category',

		//Admin
		'admin{pages}'                             => 'index',
		'admin/{id}/{url_title}'                   => '_edit',
		'admin/categories/add'                     => '_categories_add',
		'admin/categories/{id}/{url_title}'        => '_categories_edit',
		'admin/categories/delete/{id}/{url_title}' => '_categories_delete'
	);

	public static function permissions()
	{
		return array(
			'default' => array(
				'access'  => array(
					array(
						'title'  => 'Actualités',
						'icon'   => 'file-text-o',
						'access' => array(
							'add_news' => array(
								'title' => 'Ajouter',
								'icon'  => 'fa-plus',
								'admin' => TRUE
							),
							'modify_news' => array(
								'title' => 'Modifier',
								'icon'  => 'fa-edit',
								'admin' => TRUE
							),
							'delete_news' => array(
								'title' => 'Supprimer',
								'icon'  => 'fa-trash-o',
								'admin' => TRUE
							)
						)
					),
					array(
						'title'  => 'Catégories',
						'icon'   => 'fa-align-left',
						'access' => array(
							'add_news_category' => array(
								'title' => 'Ajouter une catégorie',
								'icon'  => 'fa-edit',
								'admin' => TRUE
							),
							'modify_news_category' => array(
								'title' => 'Modifier une catégorie',
								'icon'  => 'fa-trash-o',
								'admin' => TRUE
							),
							'delete_news_category' => array(
								'title' => 'Supprimer une catégorie',
								'icon'  => 'fa-flag',
								'admin' => TRUE
							)
						)
					)
				)
			)
		);
	}

	public function comments($news_id)
	{
		$news = $this->db	->select('title')
							->from('nf_news_lang')
							->where('news_id', $news_id)
							->where('lang', $this->config->lang)
							->row();

		if ($news)
		{
			return array(
				'title' => $news,
				'url'   => 'news/'.$news_id.'/'.url_title($news).'.html'
			);
		}
	}
	
	public function settings()
	{
		$this	->form
				->add_rules(array(
					'news_per_page' => array(
						'label' => '{lang news_per_page}',
						'value' => $this->config->news_per_page,
						'type'  => 'number',
						'rules' => 'required'
					)
				))
				->add_submit($this('edit'))
				->add_back('admin/addons.html#modules');

		if ($this->form->is_valid($post))
		{
			$this->config('news_per_page', $post['news_per_page']);
			
			redirect_back('admin/addons.html#modules');
		}

		return new Panel(array(
			'content' => $this->form->display()
		));
	}
}

/*
NeoFrag Alpha 0.1.4
./modules/news/news.php
*/