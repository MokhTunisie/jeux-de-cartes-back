#!/bin/bash

RESET='\e[0m'
RED='\e[31m'
GREEN='\e[32m'
YELLOW='\e[33m'


#----------------------------------------------------------------------------------------------------------------------
# FUNCTIONS
#----------------------------------------------------------------------------------------------------------------------

function __displayBox() #(color, message)
{
    caption=$(printf "%-72s" "${2}")

    echo -e "${1}"
    echo -e "┌──────────────────────────────────────────────────────────────────────────┐"
    echo -e "│ ${RESET}${caption}${1} │"
    echo -e "└──────────────────────────────────────────────────────────────────────────┘"
    echo -e "${RESET}"
}

function __checkPhpStan()
{
    task=$(printf "%-40s" "PHPSTAN - PHP Static Analyzer")

    output=$(docker-compose exec php ./vendor/bin/phpstan analyse)
    RETVAL=$?

    if [[ $RETVAL != 0 ]]
    then
        hasErrors=1

        echo -e "    ${task}"
        echo -e "${output}";
    else
        echo -e "    ${task}"
    fi

    if [[ $hasErrors == 1 ]]
    then
        __displayBox ${RED} 'GIT PUSH IS NOT ALLOWED!'
        exit 1
    fi
}

function __checkPhpUnit()
{
    task=$(printf "%-40s" "PhpUnit")

    output=$(docker-compose exec php ./vendor/bin/phpunit)
    RETVAL=$?

    if [[ $RETVAL != 0 ]]
    then
        hasErrors=1

        echo -e "    ${task}"
        echo -e "${output}";
    else
        echo -e "    ${task}"
    fi

    if [[ $hasErrors == 1 ]]
    then
        __displayBox ${RED} 'GIT PUSH IS NOT ALLOWED!'
        exit 1
    fi
}


#----------------------------------------------------------------------------------------------------------------------
# MAIN LOGIC
#----------------------------------------------------------------------------------------------------------------------

__checkPhpStan
__checkPhpUnit


__displayBox ${GREEN} 'GIT PUSH IS ALLOWED!'
exit 0
