import { Worker } from 'bullmq';
import Redis from 'ioredis';
import axios from 'axios';
import { config } from '../config';
const redis = new Redis();
export const startWorker = () => {
    const worker = new Worker('notifications', async (job) => {
        const { id, user_id, message } = job.data;
        console.log(`📨 Sending notification to user ${user_id}: ${message}`);
        await axios.patch(`${config.laravelApiUrl}/notifications/${id}`, {
            status: 'sent'
        });
    }, {
        connection: redis
    });
    worker.on('failed', (job, err) => {
        if (!job) {
            console.error(`❌ Unknown job failed: ${err.message}`);
            return;
        }
        console.error(`❌ Job ${job.id} failed: ${err.message}`);
    });
    worker.on('completed', job => {
        console.log(`✅ Job ${job.id} completed`);
    });
};
