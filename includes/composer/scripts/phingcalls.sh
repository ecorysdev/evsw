#!/bin/bash

PROJECT=/home/default/toolkit1/
PHING=$(pwd)/../../bin/phing

# If phing is available.
if [ -f $PHING ] && [ -x $PHING ] && [ -n "$1" ] ; then

  # Get phing targets.
  HOOK="composer.hook.${1//-/.}"
  PHINGCALLS=$(\
    $PHING toolkit-hooks-composer \
    -Dproject.basedir=$PROJECT \
    -Dproject.build.haltonerror.props.validate=false \
    -logger phing.listener.AnsiColorLogger \
    -find | grep -o "$HOOK=.*" | cut -f2- -d=\
  )

printf "\n\n--PHING CAllS--\n"
printf "$PHINGCALLS \n\n"

  # Execute targets, if any.
  if [ -n "$PHINGCALLS" ] ; then
    $PHING \
      $PHINGCALLS \
      -Dproject.basedir=$PROJECT \
      -Dproject.build.haltonerror.props.validate=false \
      -logger phing.listener.AnsiColorLogger \
      -find
  fi

else
  # Notify user that no phing targets can be executed for the hook.
  echo "Phing unavailable:"
  echo "No composer hooks will be executed for $1."
fi
