<?php

namespace Tests\Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;
use Dealroom\SocialsHelpers\Normalizers\FacebookPageNormalizer;
use Dealroom\SocialsHelpers\Normalizers\FacebookProfileNormalizer;
use Dealroom\SocialsHelpers\Normalizers\Factory;
use Dealroom\SocialsHelpers\Normalizers\NormalizerInterface;
use Dealroom\SocialsHelpers\Normalizers\TwitterNormalizer;
use Dealroom\SocialsHelpers\Parser;
use PHPUnit\Framework\TestCase;

class NormalizersTest extends TestCase
{
    public function testNormalizersFactory(): void
    {
        $twitterNormalizer = Factory::getForPlatform(Parser::PLATFORM_TWITTER);
        $facebookPageNormalizer = Factory::getForPlatform(Parser::PLATFORM_FACEBOOK_PAGE);
        $facebookProfileNormalizer = Factory::getForPlatform(Parser::PLATFORM_FACEBOOK_PROFILE);

        $this->assertInstanceOf(TwitterNormalizer::class, $twitterNormalizer);
        $this->assertInstanceOf(FacebookPageNormalizer::class, $facebookPageNormalizer);
        $this->assertInstanceOf(FacebookProfileNormalizer::class, $facebookProfileNormalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $twitterNormalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $facebookPageNormalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $facebookProfileNormalizer);
    }

    public function testTwitterNormalizer(): void
    {
        $twitterNormalizer = Factory::getForPlatform(Parser::PLATFORM_TWITTER);

        $values = [
            'http://www.twitter.com/codeschool/dasd?dsadasd' => 'https://twitter.com/codeschool',
            'https://www.twitter.com/codeschool?dasdad' => 'https://twitter.com/codeschool',
            'https://www.twitter.com/Codeschool?dasdad' => 'https://twitter.com/codeschool',
            'http://twitter.com/codeschool/dasd?dsadasd' => 'https://twitter.com/codeschool',
            'https://twitter.com/codeschool?dasdad' => 'https://twitter.com/codeschool',
            'https://twitter.com/#!/codeschool?dasdad' => 'https://twitter.com/codeschool',
            'https://twitter.com/@codeschool?dasdad' => 'https://twitter.com/codeschool',
            'https://twitter.com/code-school' => 'https://twitter.com/code',
            'https://twitter.com/code_school' => 'https://twitter.com/code_school',
            'https://twitter.com/greentextbooks#' => 'https://twitter.com/greentextbooks',
            'https://twitter.com/shopbop#cs=ov=73421773243,os=1,link=footerconnecttwitterlink\',' => 'https://twitter.com/shopbop',
            'https://twitter.com/share' => null,
        ];

        foreach ($values as $source => $result) {
            if (!$result) {
                $this->expectException(NormalizeException::class);
            }
            $this->assertEquals($result, $twitterNormalizer->normalize($source));
        }
    }

    public function testFacebookPageNormalizer(): void
    {
        $facebookPageNormalizer = Factory::getForPlatform(Parser::PLATFORM_FACEBOOK_PAGE);

        $values = [
            'https://www.facebook.com/dizzain/?pnref=lhc' => 'https://www.facebook.com/dizzain',
            'http://www.facebook.com/dizzain?pnref=lhc' => 'https://www.facebook.com/dizzain',
            'http://facebook.com/dizzain?pnref=lhc' => 'https://www.facebook.com/dizzain',
            'https://facebook.com/dizzain?pnref=lhc' => 'https://www.facebook.com/dizzain',
            'https://facebook.com/Dizzain?pnref=lhc' => 'https://www.facebook.com/dizzain',
            'https://www.facebook.com/pages/fasdfadsfasdfsadf/126287147568059?pnref=lhc' => 'https://www.facebook.com/pages/fasdfadsfasdfsadf/126287147568059',
            'https://www.facebook.com/PHPtoday-1025912177431644/?fref=ts' => 'https://www.facebook.com/phptoday-1025912177431644',
            'http://www.facebook.com/pages/The-bloomtrigger-project/125218650866978/fasdfas?asdas' => 'https://www.facebook.com/pages/the-bloomtrigger-project/125218650866978',
            'http://www.facebook.com/pages/DealMarket/157833714232772' => 'https://www.facebook.com/pages/dealmarket/157833714232772',
            'http://www.facebook.com/#!/pages/dealmarket/157833714232772' => 'https://www.facebook.com/pages/dealmarket/157833714232772',
            'http://www.facebook.com/home.php?tests#!/pages/dealmarket/157833714232772' => null,
            'http://www.facebook.com/pages/san-diego-ca/layer3-security-services/207635209271099' => 'https://www.facebook.com/pages/san-diego-ca/layer3-security-services/207635209271099',
            'http://www.facebook.com/pages/agility+inc./114838698562760' => 'https://www.facebook.com/pages/agility+inc./114838698562760',
            'http://www.facebook.com/pages/karen-mali-m%c3%bc%c5%9favirlik-logo-muhasebe/194296120603783' => 'https://www.facebook.com/pages/karen-mali-m%c3%bc%c5%9favirlik-logo-muhasebe/194296120603783',
            'http://www.facebook.com//pages/custom-case-guy/1445342082363874' => 'https://www.facebook.com/pages/custom-case-guy/1445342082363874',
            'https://en-gb.facebook.com/wonderbill/' => 'https://www.facebook.com/wonderbill/',
            'https://business.facebook.com/TectradeHQ/?business_id=284925295380988&ref=bookmarks' => 'https://www.facebook.com/TectradeHQ',
            'https://web.facebook.com/dermexpert/' => 'https://www.facebook.com/dermexpert',
            'https://m.facebook.com/umadic1/' => 'https://www.facebook.com/umadic1',
            'https://p-upload.facebook.com/epicvue/' => 'https://www.facebook.com/epicvue',
            'https://www.facebook.com/pages/Torrent-Pharmaceuticals-Limited/398754970290333' => 'https://www.facebook.com/pages/Torrent-Pharmaceuticals-Limited/398754970290333',
            'https://en-gb.facebook.com/pages/Torrent-Pharmaceuticals-Limited/398754970290333' => 'https://www.facebook.com/pages/Torrent-Pharmaceuticals-Limited/398754970290333',
            'https://www.facebook.com/KitVita-කියවීමේ-නිදහස්-විධිය-102446816592434/' => 'https://www.facebook.com/KitVita-කියවීමේ-නිදහස්-විධිය-102446816592434',
            'https://www.facebook.com/pages/කියවීමේ-නිදහස්-විධිය/398754970290333' => 'https://www.facebook.com/pages/කියවීමේ-නිදහස්-විධිය/398754970290333',
            'https://www.facebook.com/Пивотека-1383152971928719/' => 'https://www.facebook.com/Пивотека-1383152971928719',
        ];

        foreach ($values as $source => $result) {
            if (!$result) {
                $this->expectException(NormalizeException::class);
            }
            $this->assertEquals($result, $facebookPageNormalizer->normalize($source));
        }
    }

    public function testFacebookProfileNormalizer(): void
    {
        $facebookProfileNormalizer = Factory::getForPlatform(Parser::PLATFORM_FACEBOOK_PROFILE);

        $values = [
            'http://www.facebook.com/people/_/100000049946330' => 'https://www.facebook.com/people/_/100000049946330',
            'http://www.facebook.com/profile.php?id=1294422029' => 'https://www.facebook.com/profile.php?id=1294422029',
            'http://www.facebook.com/profile.php?id=1294422029/' => 'https://www.facebook.com/profile.php?id=1294422029',
            'http://facebook.com/profile.php?id=1294422029' => 'https://www.facebook.com/profile.php?id=1294422029',
            'http://facebook.com/profile.php?id=1294422029&foo=bar' => 'https://www.facebook.com/profile.php?id=1294422029',
        ];

        foreach ($values as $source => $result) {
            if (!$result) {
                $this->expectException(NormalizeException::class);
            }
            $this->assertEquals($result, $facebookProfileNormalizer->normalize($source));
        }
    }
}