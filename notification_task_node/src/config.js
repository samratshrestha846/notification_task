require('dotenv').config();

module.exports = {
    port: process.env.PORT ? Number(process.env.PORT) : 5000,
    laravelApiUrl: process.env.LARAVEL_API_URL || 'http://localhost:8000/api',
    redisOptions: {
        host: process.env.REDIS_HOST || '127.0.0.1',
        port: process.env.REDIS_PORT ? Number(process.env.REDIS_PORT) : 6379,
        maxRetriesPerRequest: null
    }
};