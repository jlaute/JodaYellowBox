#!/usr/bin/env bash

commit=$1
if [ -z ${commit} ]; then
    commit=$(git tag --sort=-creatordate | head -1)
    if [ -z ${commit} ]; then
        commit="master";
    fi
fi

# Remove old release
rm -rf JodaYellowBox JodaYellowBox-*.zip

# Build new release
mkdir -p JodaYellowBox
git archive ${commit} | tar -x -C JodaYellowBox
composer install --no-dev -n -o -d JodaYellowBox
zip -r JodaYellowBox-${commit}.zip JodaYellowBox
