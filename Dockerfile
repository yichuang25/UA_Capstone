# Uses PHP 7.4.28
From wordpress:5.9.2

# Update Packages
RUN apt-get update && apt-get install -y \
    unixodbc-dev \
    curl \
    apt-transport-https \
    unixodbc-dev \
    libgssapi-krb5-2 \
    gnupg2

# Install Microsoft OBDC Driver for SQL Server
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list

RUN apt-get update
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql17

# add sqlcmd
RUN ACCEPT_EULA=Y apt-get install -y mssql-tools
RUN echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
RUN . ~/.bashrc

# Generate Local
RUN echo 'export PATH="$PATH:/usr/sbin"' >> ~/.bashrc
RUN . ~/.bashrc
# RUN sed -i 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/g' /etc/locale.gen
# RUN locale-gen #TODO reinstall local-gen

# Install the PHP drivers for Microsoft SQL Server
RUN pecl install sqlsrv
RUN pecl install pdo_sqlsrv
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv
