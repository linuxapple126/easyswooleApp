<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/8/2
 * Time: 17:50
 */

namespace App\Utility;

use EasySwoole\EasySwoole\Config;
use Elasticsearch\ClientBuilder;

class ElasticSearch
{
    private $server;

    /**
     * 初始化连接
     */
    public function __construct()
    {
        if (!$this->server) {
            $esConfig = Config::getInstance()->getConf('ELASTIC');
            $this->server = ClientBuilder::create()->setHosts($esConfig)->build();
        }
    }

    /**
     * 创建一个索引（index,类似于创建一个库）
     * @param $index
     * @return array|callable
     */
    public function createIndex($index)
    {
        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,  // 分片 默认5
                    'number_of_replicas' => 1  // 副本、备份 默认1
                ]
            ]
        ];
        $response = $this->server->indices()->create($params);

        return $response;
    }

    /**
     *  删除一个索引（index,类似于删除一个库）
     * @param $index
     * @return array|callable
     */
    public function deleteIndex($index)
    {
        $params = [
            'index' => $index
        ];
        $response = $this->server->indices()->delete($params);

        return $response;
    }


    /**
     * 更改或增加user索引的映射
     * 在创建完user的index后使用
     * @param $index
     * @param $type
     * @return array|callable
     */
    public function putMappingsForUser($index, $type)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'body' => [
                $type => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        // name 是需要搜索分词的字段
                        'name' => [
                            'type' => 'text',
                            'analyzer' => 'ik_smart',
                            'search_analyzer' => 'ik_smart',
                            'search_quote_analyzer' => 'ik_smart'
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->server->indices()->putMapping($params);

        return $response;
    }

    /**
     * 更改或增加 文章 post 索引的映射
     * 在创建完post的index后使用,目前已经集成到es:init命令中
     * @param $index
     * @param $type
     * @return array|callable
     */
    public function putMappingsForPost($index, $type)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'body' => [
                $type => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                            'analyzer' => 'ik_smart',
                            'search_analyzer' => 'ik_smart',
                            'search_quote_analyzer' => 'ik_smart'
                        ],
                        'content' => [
                            'type' => 'text',
                            'analyzer' => 'ik_smart',
                            'search_analyzer' => 'ik_smart',
                            'search_quote_analyzer' => 'ik_smart'
                        ],
                    ]
                ]
            ]
        ];
        $response = $this->server->indices()->putMapping($params);

        return $response;
    }


    /**
     * 创建一条数据（索引一个文档）
     * @param $index
     * @param $type
     * @param $id
     * @param $body
     * @return array|callable
     */
    public function createDoc($index, $type, $id, $body)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'body' => $body,
        ];
        $response = $this->server->index($params);

        return $response;
    }


    /**
     * 获取一个文档（对应上面createDoc）
     * @param $index
     * @param $type
     * @param $id
     * @return array|callable
     */
    public function getDoc($index, $type, $id)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id
        ];
        $response = $this->server->get($params);

        return $response;
    }


    /**
     * 搜索文档 doc 单字段
     * @param $index
     * @param $type
     * @param $query
     * @return mixed
     */
    public function searchOne($index, $type, $query)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'body' => [
                'query' => [
                    'match' => [
                        'title' => $query
                    ]
                ]
            ]
        ];
        $response = $this->server->search($params);

        return $response['hits'];
    }

    /**
     * 搜索文档 doc 多字段
     * @param $index
     * @param $type
     * @param $query
     * @return mixed
     */
    public function searchMore($index, $type, $query)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        "type" => "best_fields",
                        'operator' => 'or',
                        'fields' => ['title', 'content']
                    ]
                ]
            ]
        ];
        $response = $this->server->search($params);

        return $response['hits'];
    }


    /**
     * 删除一条记录（文档） doc
     * @param $index
     * @param $type
     * @param $id
     * @return array|callable
     */
    public function delete($index, $type, $id)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id
        ];
        $response = $this->server->delete($params);

        return $response;
    }

    public function __destruct()
    {
        unset($this->server);
    }
}