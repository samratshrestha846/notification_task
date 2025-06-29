const { Queue } = require('bullmq');
const Redis = require('ioredis');
const axios = require('axios');
const { redisOptions, laravelApiUrl } = require('../config');

const redis = new Redis(redisOptions);
const notificationQueue = new Queue('notifications', { connection: redis });

async function notificationRoutes(fastify, options) {
    fastify.get('/', async (request, reply) => {
        return reply.send({ message: 'Fastify server is running!' });
    });
    fastify.get('/notifications/recent', async (request, reply) => {
        try {
            const response = await axios.get(`${laravelApiUrl}/notifications/recent`);
            reply.send(response.data);
        } catch (error) {
            fastify.log.error('Error fetching recent notifications:', error.message);
            reply.code(500).send({ error: 'Failed to fetch recent notifications' });
        }
    });

    fastify.get('/notifications/summary', async (request, reply) => {
        try {
            const response = await axios.get(`${laravelApiUrl}/notifications/summary`);
            reply.send(response.data);
        } catch (error) {
            fastify.log.error('Error fetching notifications summary:', error.message);
            reply.code(500).send({ error: 'Failed to fetch notifications summary' });
        }
    });
}

module.exports = notificationRoutes;