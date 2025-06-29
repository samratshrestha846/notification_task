import 'dotenv/config';
export const config = {
    port: process.env.PORT ? Number(process.env.PORT) : 3000,
    laravelApiUrl: process.env.LARAVEL_API_URL || 'http://localhost:8000/api'
};
