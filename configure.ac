## Author: Mo McRoberts <mo.mcroberts@bbc.co.uk>
##
## Copyright (c) 2017 BBC
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

AC_INIT([alpha],m4_esyscmd([/bin/sh scripts/get-version.sh]),[noreply@bbc.co.uk],,[https://github.com/bbc/alpha])
AC_CONFIG_MACRO_DIR([m4])
AC_CONFIG_AUX_DIR([scripts])
AC_PREFIX_DEFAULT([/opt/bbc])

AM_INIT_AUTOMAKE([foreign])
AM_MAINTAINER_MODE

dnl Configuration options

AC_ARG_WITH([webdir],[AS_HELP_STRING([--with-webdir=PATH],[Install web apps beneath PATH (defaults to ${datadir}/${PACKAGE})])],[webdir=$withval],[webdir='${datadir}/${PACKAGE}'])
AC_SUBST([webdir])

AC_ARG_WITH([staticdir],[AS_HELP_STRING([--with-staticdir=PATH],[Install static resources beneath PATH (defaults to ${webdir}/static)])],[staticdir=$withval],[staticdir='${webdir}/static'])
AC_SUBST([staticdir])

AC_ARG_WITH([componentsdir],[AS_HELP_STRING([--with-componentsdir=PATH],[Install components beneath PATH (defaults to ${webdir}/components)])],[componentsdir=$withval],[componentsdir='${webdir}/components'])
AC_SUBST([componentsdir])

dnl Checks for tools

AC_PROG_LN_S

dnl Bundled components

AC_DEFUN([ALPHA_COMPONENT],[
if test -d $srcdir/components/$1 ; then
    AC_CONFIG_SUBDIRS([components/$1])
    COMPONENTS="$COMPONENTS $1"
fi
])dnl

COMPONENTS=""

ALPHA_COMPONENT([boilerplate])
ALPHA_COMPONENT([player])
ALPHA_COMPONENT([clock])
ALPHA_COMPONENT([weather])
ALPHA_COMPONENT([card])
ALPHA_COMPONENT([guide])
ALPHA_COMPONENT([quiz])
ALPHA_COMPONENT([gallery])
ALPHA_COMPONENT([map])
ALPHA_COMPONENT([picture])

AC_SUBST([COMPONENTS])

dnl Installation directories

webconfigdir='${webdir}/config'
AC_SUBST([webconfigdir])
weblibdir='${webdir}/lib'
AC_SUBST([weblibdir])
webpublicdir='${webdir}/public'
AC_SUBST([webpublicdir])
webtemplatesdir='${webdir}/templates'
AC_SUBST([webtemplatesdir])
webstaticdir='${staticdir}'
AC_SUBST([webstaticdir])

dnl Basic build machinery

AC_CONFIG_FILES([
Makefile
m4/Makefile
scripts/Makefile
])

dnl Experience layer

AC_CONFIG_FILES([
experience/Makefile
experience/config/Makefile
experience/config/defaults.php
experience/lib/Makefile
experience/public/Makefile
experience/static/Makefile
experience/static/alpha/Makefile
experience/static/alpha/current/Makefile
experience/static/alpha/current/css/Makefile
experience/static/alpha/current/js/Makefile
experience/static/alpha/current/img/Makefile
experience/static/alpha/current/fonts/Makefile
experience/templates/Makefile
experience/templates/alpha/Makefile
])

dnl Component layer

AC_CONFIG_FILES([
components/Makefile
components/public/Makefile
])

AC_OUTPUT
