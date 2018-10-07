<?php

namespace controllers;

class TestController
{
    /**
     * 前端页面
     */

    public function f_fengmian()
    {
        view('front.fengmian');
    }

    public function f_about()
    {
        view('front.about');
    }

    public function f_info()
    {
        view('front.info');
    }

    public function f_list()
    {
        view('front.list');
    }

    public function f_share()
    {
        view('front.share');
    }

    public function f_time()
    {
        view('front.time');
    }

    /**
     * 后端页面
     */
    public function article_add()
    {
        view('admin.articles.article_add');
    }

    public function article_del()
    {
        view('admin.articles.article_del');
    }

    public function article_edit()
    {
        view('admin.articles.article_edit');
    }

    public function article_list()
    {
        view('admin.articles.article_list');
    }

    public function billboard_add()
    {
        view('admin.billboard.billboard_add');
    }

    public function billboard_edit()
    {
        view('admin.billboard.billboard_edit');
    }

    public function billboard_list()
    {
        view('admin.billboard.billboard_list');
    }

    public function feedback_edit()
    {
        view('admin.comments.feedback_edit');
    }

    public function comment_list()
    {
        view('admin.comments.comment_list');
    }

    public function feedback_list()
    {
        view('admin.comments.feedback_list');
    }

    public function istrators_add()
    {
        view('admin.istrators.istrators_add');
    }

    public function istrators_cate()
    {
        view('admin.istrators.istrators_cate');
    }

    public function istrators_edit()
    {
        view('admin.istrators.istrators_edit');
    }

    public function istrators_list()
    {
        view('admin.istrators.istrators_list');
    }

    public function user_add()
    {
        view('admin.users.user_add');
    }

    public function user_del()
    {
        view('admin.users.user_del');
    }

    public function user_edit()
    {
        view('admin.users.user_edit');
    }

    public function user_kiss()
    {
        view('admin.users.user_kiss');
    }

    public function user_level()
    {
        view('admin.users.user_level');
    }

    public function user_list()
    {
        view('admin.users.user_list');
    }

    public function user_password()
    {
        view('admin.users.user_password');
    }

    public function user_show()
    {
        view('admin.users.user_show');
    }

    public function user_view()
    {
        view('admin.users.user_view');
    }

    public function kiss_add()
    {
        view('admin.kiss.kiss_add');
    }

    public function kiss_edit()
    {
        view('admin.kiss.kiss_edit');
    }

    public function level_add()
    {
        view('admin.level.level_add');
    }

    public function label()
    {
        view('admin.label.label');
    }

    public function cate_edit()
    {
        view('admin.label.cate_edit');
    }

    public function level_edit()
    {
        view('admin.level.level_edit');
    }

    public function role_add()
    {
        view('admin.role.role_add');
    }

    public function role_edit()
    {
        view('admin.role.role_edit');
    }


    public function data_edit()
    {
        view('admin.sys.data_edit');
    }

    public function link_edit()
    {
        view('admin.link_edit');
    }

}
