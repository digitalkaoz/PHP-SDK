<?php
/**
 * @author nils.droege@project-collins.com
 * (c) Collins GmbH & Co KG
 */

namespace Collins\ShopApi\Test\Unit\Model;

use Collins\ShopApi\Model\Image;
use Collins\ShopApi;

class ImageTest extends AbstractModelTest
{
    public function testFromJson()
    {
        $jsonObject = $this->getJsonObject('image.json');

        $image = Image::createFromJson($jsonObject);
        $this->assertNotNull($image->getBaseUrl());

        $this->assertEquals('hash1', $image->getHash());
        $this->assertEquals('.jpg', $image->getExt());
        $this->assertEquals('image/jpeg', $image->getMimetype());
        $this->assertEquals(12345678, $image->getFilesize());
        $this->assertEquals(array('tag1', 'tag2'), $image->getTags());

        $imageSize = $image->getImageSize();
        $this->assertInstanceOf('Collins\\ShopApi\\Model\\ImageSize', $imageSize);
        $this->assertEquals(1400, $imageSize->getWidth());
        $this->assertEquals(2000, $imageSize->getHeight());

        $image->setBaseUrl();
        $this->assertStringStartsWith('/hash1', $image->getUrl());
        $image->setBaseUrl(false);
        $this->assertStringStartsWith('/hash1', $image->getUrl());
        $image->setBaseUrl(null);
        $this->assertStringStartsWith('/hash1', $image->getUrl());
        $image->setBaseUrl('');
        $this->assertStringStartsWith('/hash1', $image->getUrl());
        $this->assertStringStartsWith('/hash1?width=123&height=456', $image->getUrl(123, 456));

        $shopApi = new ShopApi('appid', 'pw');
        $this->assertStringStartsWith(ShopApi::IMAGE_URL_LIVE . '/hash1', $image->getUrl());
        $shopApi->setBaseImageUrl('http://domain.tld');
        $this->assertStringStartsWith('http://domain.tld/hash1', $image->getUrl());
        $shopApi->setBaseImageUrl('http://domain.tld/');
        $this->assertStringStartsWith('http://domain.tld/hash1', $image->getUrl());
        $shopApi->setBaseImageUrl(false);
        $this->assertStringStartsWith('/hash1', $image->getUrl());
        $shopApi->setBaseImageUrl(null);
        $this->assertStringStartsWith(ShopApi::IMAGE_URL_LIVE . '/hash1', $image->getUrl());
    }
}
 