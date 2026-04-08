FROM php:8.4-fpm

RUN apt-get update -y && apt-get upgrade -y && apt-get clean all && \
    apt-get install -y git bash sudo jq zsh locales locales-all zip unzip libpq-dev

ARG PATH=${PATH}
RUN curl -s https://getcomposer.org/installer | php && echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc && \
    mv composer.phar /usr/local/bin/composer

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

RUN pecl install ast && echo extension=ast.so > /usr/local/etc/php/conf.d/docker-pecl-ast.ini

# xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# uninstall opcache for dev needs
RUN rm /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

RUN echo "root:root" | chpasswd
RUN echo "www-data:www-data" | chpasswd
RUN usermod -aG sudo www-data
RUN echo "www-data ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers

RUN chsh -s /bin/zsh www-data
RUN chsh -s /bin/zsh root
RUN echo 'echo y | sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"' > /tmp/000
RUN chmod +x /tmp/000
RUN chmod 0777 /tmp/000
RUN sh /tmp/000
RUN chmod -R 0777 /var/www

RUN export LC_ALL=en_US.UTF-8 && export LANG=en_US.UTF-8 && export LANGUAGE=en_US.UTF-8 && export LC_TYPE=en_US.UTF-8
RUN locale-gen

ARG USER_ID=1000
ARG GROUP_ID=1000
USER "${USER_ID}:${GROUP_ID}"
USER www-data
RUN sh /tmp/000
RUN sed -i "s/^ZSH_THEME=.*/ZSH_THEME=\"agnoster\"/" /var/www/.zshrc

ENTRYPOINT []
CMD ["php-fpm"]
