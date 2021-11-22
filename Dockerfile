FROM webdevops/php-apache-dev:7.4

# Fuseaux horaire
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime

# Install NodeJS
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -

# Install Yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list

# Install cURL and htop
RUN apt-get update && apt-get install -y \
        htop \
        nodejs \
        yarn \
        xfonts-75dpi \
        xfonts-base

# Upgrade composer vers V2
RUN cd app/ && composer self-update