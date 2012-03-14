#!/bin/sh

##
# Install memcached and dependencies smoothly on Mac OS X.
#
# Newer versions of these libraries are available and may work better on Mac OS X.
#
# See also http://topfunky.net/svn/shovel/memcached/install-memcached-linux.sh
#
# USE AT YOUR OWN RISK.
#
# AUTHOR: Geoffrey Grosenbach http://nubyonrails.com
#
# Some fixes are from http://blog.segment7.net/articles/2006/03/02/fast-memcached-on-os-x
#
# AFTER RUNNING THIS SCRIPT:
#
# Set the environment variable EVENT_NOKQUEUE to 1
# * csh and derivatives: setenv EVENT_NOKQUEUE 1
# * sh and derivatives (like bash): export EVENT_NOKQUEUE=1
# 
# You may also need to add /usr/local to your PATH.
#

PREFIX=/usr/local

mkdir src
cd src

# Install libevent dependency
curl -O http://www.monkey.org/~provos/libevent-1.1b.tar.gz
tar xfz libevent-1.1b.tar.gz
cd libevent-1.1b
./configure --prefix=${PREFIX} && make
sudo make install
cd ..

# Install memcached and fixes
curl -O http://www.danga.com/memcached/dist/memcached-1.1.12.tar.gz
tar xfz memcached-1.1.12.tar.gz
cd memcached-1.1.12
./configure --prefix=${PREFIX}

# in Makefile
# LDFLAGS =  -L/lib
# LDFLAGS =  -L${libdir}
sed -e 's/-L\/lib/-L${libdir}/' Makefile > Makefile.new
mv Makefile.new Makefile

# also in Makefile
# CFLAGS = -g -O2 -I/include
# CFLAGS = -g -O2 -I${includedir}
sed -e 's/-I\/include/-I${includedir}/' Makefile > Makefile.new
mv Makefile.new Makefile

# insert in memcached.c...
# #undef TCP_NOPUSH
# #ifdef TCP_NOPUSH
curl -O http://topfunky.net/svn/shovel/memcached/fixmemcached_c.rb
ruby fixmemcached_c.rb > memcached.c.new
mv memcached.c.new memcached.c

make
sudo make install
cd ../..

echo "Installation complete. Please add EVENT_NOKQUEUE=1 to your shell environment."
