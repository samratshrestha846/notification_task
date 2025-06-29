import Fastify from 'fastify';
process.on('exit', (code) => {
    console.log(`Process exit event with code: ${code}`);
});
process.on('SIGINT', () => {
    console.log('Process interrupted (SIGINT)');
    process.exit(0);
});
process.on('uncaughtException', (err) => {
    console.error('Uncaught Exception:', err);
});
process.on('unhandledRejection', (reason) => {
    console.error('Unhandled Rejection:', reason);
});
async function start() {
    console.log('Starting Fastify app...');
    const app = Fastify({ logger: true });
    app.get('/', async () => {
        return { hello: 'world' };
    });
    try {
        console.log('Listening on port 5000...');
        await app.listen({ port: 5000, host: '127.0.0.1' });
        console.log('Server started!');
    }
    catch (error) {
        console.error('Failed to start server:', error);
        process.exit(1);
    }
}
start();
