##  Author: Mo McRoberts
##
##  Copyright (c) 2017 BBC
##
##  Licensed under the Apache License, Version 2.0 (the "License");
##  you may not use this file except in compliance with the License.
##  You may obtain a copy of the License at
##
##      http://www.apache.org/licenses/LICENSE-2.0
##
##  Unless required by applicable law or agreed to in writing, software
##  distributed under the License is distributed on an "AS IS" BASIS,
##  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
##  See the License for the specific language governing permissions and
##  limitations under the License.

dist_webconfig_DATA = config.example.php defaults.php

EXTRA_DIST = defaults.php.in apache2.conf

noinst_DATA = config.php

DISTCLEANFILES = defaults.php

config.php: config.example.php
	if ! test -e config.php ; then \
		cp $(srcdir)/config.example.php config.php ; \
	fi
