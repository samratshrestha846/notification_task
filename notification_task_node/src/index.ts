const Fastify = require('fastify');
const notificationRoutes = require('./routes/notificationRoutes');
const { startWorker } = require('./queue/worker');
const { port } = require('./config');

async function start() {
    const app = Fastify({ logger: true });

    await app.register(notificationRoutes);

    try {
        await app.listen({ port, host: '127.0.0.1' });
        console.log(`Server started at http://127.0.0.1:${port}`);
    } catch (err) {
        app.log.error('Failed to start server:', err);
        process.exit(1);
    }

    startWorker();
}

start();