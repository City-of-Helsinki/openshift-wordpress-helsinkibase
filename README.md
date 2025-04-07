# Basic Helsinki WP site


# Docker build example
    docker build --build-arg COMPOSER_AUTH='{"github-oauth": {"github.com": "ghp_your_github_pat"}}' -t wp-helsinki-base .

# Docker build example, repositories and packages provided as build args
    docker build \
    --build-arg COMPOSER_AUTH='{"github-oauth": {"github.com": "ghp_your_github_pat"}}' \
    --build-arg COMPOSER_REPOSITORIES="wordpress-helfi-helsinkiteema vcs https://github.com/City-of-Helsinki/wordpress-helfi-helsinkiteema | wordpress-helfi-site-core vcs https://github.com/City-of-Helsinki/wordpress-helfi-site-core" \
    --build-arg COMPOSER_PACKAGES="city-of-helsinki/wordpress-helfi-helsinkiteema city-of-helsinki/wordpress-helfi-site-core" \
    -t wp-helsinki-base.

Repository format: repo_name type url (separated with "|" pipe character)