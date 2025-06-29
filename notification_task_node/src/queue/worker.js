const { Worker } = require('bullmq');
const Redis = require('ioredis');
const axios = require('axios');
const { laravelApiUrl, redisOptions } = require('../config');

const redis = new Redis(redisOptions);

function startWorker() {
    const worker = new Worker('notifications', async (job) => {
        console.log('Processing job:', job.id, 'data:', job.data);
        const { user_id, message } = job.data;
        console.log(`Sending notification to user ${user_id}: ${message}`);

        try {
            const response = await axios.post(`${laravelApiUrl}/notifications`, {
                user_id,
                message
            });
            console.log('Laravel API response:', response.data);
        } catch (error) {
            console.error('Error sending notification to Laravel:', error.message);
            throw error;
        }
    }, { connection: redis });

    worker.on('failed', (job, err) => {
        if (!job) {
            console.error(`Unknown job failed: ${err.message}`);
            return;
        }
        console.error(`Job ${job.id} failed: ${err.message}`);
    });

    worker.on('completed', (job) => {
        console.log(`Job ${job.id} completed`);
    });
}

module.exports = { startWorker };