# Use an official PHP runtime as a parent image
FROM php:8.1-cli

# Set the working directory
WORKDIR /app

# Copy the current directory contents into the container
COPY . /app

# Expose the port PHP runs on
EXPOSE 10000

# Start PHP's built-in server
CMD ["php", "-S", "0.0.0.0:10000"]
