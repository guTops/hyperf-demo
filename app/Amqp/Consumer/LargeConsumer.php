<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Amqp\Consumer;

use App\Amqp\Producer\LargeProducer;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use Hyperf\Utils\Codec\Json;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * @Consumer(exchange="hyperf", routingKey="large", queue="hyperf.large", name="LargeConsumer", nums=1)
 */
class LargeConsumer extends ConsumerMessage
{
    public function consume($data, AMQPMessage $message): string
    {
        $json = Json::encode($data);
        var_dump(strlen($json), isset($data['is'], $data['name'], $data['data']));

        if ($data['requeue']) {
            amqp_produce(new LargeProducer($data, false));
        }

        return Result::ACK;
    }
}
