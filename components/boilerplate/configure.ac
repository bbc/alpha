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

AC_INIT([boilerplate],m4_esyscmd([/bin/sh ../../scripts/get-version.sh]),[noreply@bbc.co.uk],,[https://github.com/bbc/alpha])
AC_CONFIG_MACRO_DIR([../../m4])
AC_CONFIG_AUX_DIR([../../scripts])

AM_INIT_AUTOMAKE([foreign])
AM_MAINTAINER_MODE

dnl Component configuration options

AC_ARG_WITH([webdir],[AS_HELP_STRING([--with-webdir=PATH],[Install web apps beneath PATH (defaults to ${datadir}/alpha)])],[webdir=$withval],[webdir='${datadir}/alpha'])
AC_SUBST([webdir])

AC_ARG_WITH([componentsdir],[AS_HELP_STRING([--with-componentsdir=PATH],[Install components beneath PATH (defaults to ${webdir}/components)])],[componentsdir=$withval],[componentsdir='${webdir}/components'])
AC_SUBST([componentsdir])

AC_ARG_WITH([staticdir],[AS_HELP_STRING([--with-staticdir=PATH],[Install static resources beneath PATH (defaults to bundling within ${componentsdir}) ${componentdir}/${PACKAGE}/static)])],[staticdir=$withval],[staticdir=''])
AC_SUBST([staticdir])

dnl Checks for tools

AC_PROG_LN_S

dnl Component installation directories

componentdir='${componentsdir}/${PACKAGE}'
AC_SUBST([componentdir])

if test x"$staticdir" = x"" ; then
    compstaticdir='${componentdir}/static/${VERSION}'
else
    compstaticdir='${staticdir}/${PACKAGE}/${VERSION}'
fi
AC_SUBST([compstaticdir])

compconfigdir='${componentdir}/config'
AC_SUBST([compconfigdir])

complibdir='${componentdir}/lib'
AC_SUBST([complibdir])

comppublicdir='${componentdir}/public'
AC_SUBST([comppublicdir])

comptemplatesdir='${componentdir}/templates'
AC_SUBST([comptemplatesdir])

AC_CONFIG_FILES([
Makefile
config/Makefile
config/defaults.php
lib/Makefile
static/Makefile
static/current/Makefile
static/current/css/Makefile
static/current/img/Makefile
static/current/js/Makefile
static/current/fonts/Makefile
public/Makefile
])

AC_OUTPUT
