KnpLabs/KnpGaufretteBundle
================

Sample configuration
----------------
    
#### Local configuration

``` yaml
# app/config/config.yml
knp_gaufrette:
    adapters:
        adapter_local:
            local:
                directory:  /home/parallel/filesystem
                create:     false

    filesystems:
        local_fs:
            adapter:    adapter_local
            alias:      adapter_fs_local
```

``` php
$container->get('knp_gaufrette.filesystem_map')->get('local_fs');
```

#### Amazon S3 configuration

``` yaml
# app/config/config.yml
knp_gaufrette:
    adapters:
        adapter_amazon:
            amazon_s3:
                amazon_s3_id:   amazonS3
                bucket_name:    bucketName
                options:
                    directory:  myDirectory

    filesystems:
        amazon_fs:
            adapter:    adapter_amazon
            alias:      adapter_fs_amazon
```

``` yaml
# app/config/services.yml
services:
    amazonS3:
        class: AmazonS3
        arguments:
            options:
                key:      '%aws_key%'
                secret:   '%aws_secret_key%'
```

``` yaml
# app/config/parameters.yml
parameters:
    aws_key: AWS KEY
    aws_secret_key: AWS SECRET KEY
    aws_region: eu-west-1
```

``` php
$container->get('knp_gaufrette.filesystem_map')->get('amazon_fs');
```

#### AwsS3 configuration

``` yaml
# app/config/config.yml
knp_gaufrette:
    adapters:
        adapter_amazon:
            aws_s3:
                service_id: 'test.aws_s3.client'
                bucket_name: 'bucketName'
                options:
                    directory: 'myDirectory'
    filesystems:
            amazon_fs:
                adapter:    adapter_amazon
                alias:      adapter_fs_amazon
```

``` yaml
# app/config/services.yml
services:
    test.aws_s3.client:
        class: Aws\S3\S3Client
        factory: [Aws\S3\S3Client, factory]
        arguments:
            - credentials: { key: %aws_key%, secret: %aws_secret_key% }
              region: %aws_region%
              version: latest
```

``` yaml
# app/config/parameters.yml
parameters:
    aws_key: AWS KEY
    aws_secret_key: AWS SECRET KEY
    aws_region: eu-west-1
```

``` php
$container->get('knp_gaufrette.filesystem_map')->get('amazon_fs');
```