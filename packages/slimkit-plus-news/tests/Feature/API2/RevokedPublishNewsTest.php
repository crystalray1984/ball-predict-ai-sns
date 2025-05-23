<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Feature\API2;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate as NewsCateModel;
use Zhiyi\Plus\Models\Tag as TagModel;
use Zhiyi\Plus\Models\TagCategory as TagCateModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Tests\TestCase;

class RevokedPublishNewsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 删除审核和通过的投稿.
     *
     * @return mixed
     */
    public function testRevokedNews()
    {
        $user = UserModel::factory()->create();
        $cate = NewsCateModel::factory()->create(););

        $news = NewsModel::factory()->create([
            'user_id' => $user->id,
            'cate_id' => $cate->id,
            'audit_status' => 1,
            'contribute_amount' => 100,
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('PUT', "/api/v2/news/categories/{$cate->id}/news/{$news->id}");
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 创建所需标签.
     *
     * @return mixed
     */
    protected function createTags()
    {
        $cate = TagCateModel::factory()->create(););
        $tags = TagModel::factory(3)->create([
            'tag_category_id' => $cate->id,
        ]);

        return $tags->pluck('id')->implode(',');
    }
}
