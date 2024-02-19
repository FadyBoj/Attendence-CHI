FROM bitnami/laravel:latest

# Copy your Laravel application code into the container
COPY . /app

EXPOSE 8000
