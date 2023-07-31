FROM gcr.io/erskine-io/devops-php-app:1.0.1-ubuntu

ARG HONE_SSH_KEY
ARG HONE_AUTH_KEY

WORKDIR /var/www/html
COPY . .

# COPY .ssh /root/.ssh/id_rsa
RUN mkdir -p /root/.ssh \
    && echo $HONE_SSH_KEY > /root/.ssh/id_rsa \
    && chmod 600 /root/.ssh/id_rsa \
    && echo "[url \"git@github.com:\"]\n\tinsteadOf = https://github.com/" >> /root/.gitconfig \
    && echo "StrictHostKeyChecking no " > /root/.ssh/config \
    && composer config --global github-oauth.github.com $HONE_AUTH_KEY

# Install application
RUN cp .env.example .env \
    && mkdir -p \
    bootstrap/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    && docker-php-ext-install exif \
    && mkdir -p node_modules \
    && chown -R www-data:www-data /var/www \
    && composer install --no-dev --no-scripts \
    && rm -rf node_modules && rm -rf package-lock.json \
    && mkdir -p public/assets/backend \
    && npm install \
    && npm run production

# Copy custom configs
COPY .docker/php/conf.d/php-custom.ini /usr/local/etc/php/conf.d/php-custom.ini
COPY .docker/supervisor/conf.d/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

VOLUME /usr/local/etc/php/conf.d

RUN ["chmod", "+x", ".docker/start.sh"]
ENTRYPOINT [ ".docker/start.sh" ]
