# The OpenStack Archive Site

This site will read a [RackSpace Cloud Files](http://www.rackspace.com/cloud/files/) container and display the contents on the page, it then uses the Akamai CDN network to allow users to download the files.

## Installing

Start by cloning the git repository and update the submodule:

    git clone git@github.com:mattrude/OpenStack-Archive.git
    cd OpenStack-Archive
    git submodule init
    git submodule update

Then setup nginx for example:

    server {
        listen 80;
        listen [::]:80;
        server_name archive.example.com;
        root /var/www/OpenStack-Archive;
        index index.php;

        location ~ \.php$ {
            fastcgi_pass php;
            fastcgi_index index.php;
            fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
        }
    }

Lastly, move `config.inc.default.php` to `config.inc.php` and update the contents to fit your needs.

## License

This is free software; you may redistribute it and/or modify it under the terms of the GNU General Public License version 2 as published by the [Free Software Foundation](http://www.fsf.org/).

                  GNU GENERAL PUBLIC LICENSE
                     Version 2, June 1991
    
    Copyright (C) 2012-2013 Matt Rude <matt@mattrude.com>
    
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

