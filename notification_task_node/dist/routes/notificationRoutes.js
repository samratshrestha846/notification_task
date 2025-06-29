import { Type } from '@sinclair/typebox';
import { Queue } from 'bullmq';
import Redis from 'ioredis';
const redis = new Redis();
const notificationQueue = new Queue('notifications', { connection: redis });
const NotificationSchema = Type.Object({
    id: Type.Number(),
    user_id: Type.Number(),
    message: Type.String()
});
export default async function (fastify) {
    fastify.post('/notifications', {
        schema: {
            body: NotificationSchema,
            response: {
                201: Type.Object({
                    jobId: Type.String(),
                    message: Type.String()
                })
            }
        },
        handler: async (req, reply) => {
            const job = await notificationQueue.add('sendNotification', req.body, {
                attempts: 5,
                backoff: {
                    type: 'exponential',
                    delay: 2000
                }
            });
            reply.code(201).send({ jobId: job.id, message: 'Queued' });
        }
    });
}
