<?php

use PHPUnit\Framework\TestCase;

class TestusersController extends TestCase
{
    public function test_if_name_can_be_assign()
    {
        $post = new Post();
        $this->assertInstanceOf(Post::class, $post->setName("Paulo Abreu"));
        $this->assertEquals("Paulo Abreu", $post->getName());
    }
}