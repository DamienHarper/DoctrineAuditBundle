<?php

namespace DH\DoctrineAuditBundle\Tests;

use DH\DoctrineAuditBundle\AuditConfiguration;
use DH\DoctrineAuditBundle\AuditEntry;
use DH\DoctrineAuditBundle\AuditReader;
use DH\DoctrineAuditBundle\Tests\Fixtures\Core\Author;
use DH\DoctrineAuditBundle\Tests\Fixtures\Core\Comment;
use DH\DoctrineAuditBundle\Tests\Fixtures\Core\Post;
use DH\DoctrineAuditBundle\Tests\Fixtures\Core\Tag;

/**
 * @covers \DH\DoctrineAuditBundle\AuditEntry
 * @covers \DH\DoctrineAuditBundle\AuditReader
 * @covers \DH\DoctrineAuditBundle\AuditConfiguration
 * @covers \DH\DoctrineAuditBundle\User\TokenStorageUserProvider
 * @covers \DH\DoctrineAuditBundle\EventSubscriber\AuditSubscriber
 * @covers \DH\DoctrineAuditBundle\EventSubscriber\CreateSchemaListener
 * @covers \DH\DoctrineAuditBundle\DBAL\AuditLogger
 */
class AuditReaderTest extends CoreTestCase
{
    public function testGetAuditConfiguration(): void
    {
        $reader = $this->getReader();

        $this->assertInstanceOf(AuditConfiguration::class, $reader->getConfiguration(), 'configuration instanceof AuditConfiguration::class');
    }

    public function testFilterIsNullByDefault(): void
    {
        $reader = $this->getReader();

        $this->assertNull($reader->getFilter(), 'filter is null by default.');
    }

    public function testFilterCanOnlyBePartOfAllowedValues(): void
    {
        $reader = $this->getReader();

        $reader->filterBy('UNKNOWN');
        $this->assertNull($reader->getFilter(), 'filter is null when AuditReader::filterBy() parameter is not an allowed value.');

        $reader->filterBy(AuditReader::ASSOCIATE);
        $this->assertSame(AuditReader::ASSOCIATE, $reader->getFilter(), 'filter is not null when AuditReader::filterBy() parameter is an allowed value.');

        $reader->filterBy(AuditReader::DISSOCIATE);
        $this->assertSame(AuditReader::DISSOCIATE, $reader->getFilter(), 'filter is not null when AuditReader::filterBy() parameter is an allowed value.');

        $reader->filterBy(AuditReader::INSERT);
        $this->assertSame(AuditReader::INSERT, $reader->getFilter(), 'filter is not null when AuditReader::filterBy() parameter is an allowed value.');

        $reader->filterBy(AuditReader::REMOVE);
        $this->assertSame(AuditReader::REMOVE, $reader->getFilter(), 'filter is not null when AuditReader::filterBy() parameter is an allowed value.');

        $reader->filterBy(AuditReader::UPDATE);
        $this->assertSame(AuditReader::UPDATE, $reader->getFilter(), 'filter is not null when AuditReader::filterBy() parameter is an allowed value.');
    }

    public function testGetEntityTableName(): void
    {
        $entities = [
            Post::class => null,
            Comment::class => null,
        ];

        $configuration = $this->createAuditConfiguration([
            'entities' => $entities,
        ]);

        $reader = $this->getReader($configuration);

        $this->assertSame('post', $reader->getEntityTableName(Post::class), 'tablename is ok.');
        $this->assertSame('comment', $reader->getEntityTableName(Comment::class), 'tablename is ok.');
    }

    /**
     * @depends testGetEntityTableName
     */
    public function testGetEntities(): void
    {
        $entities = [
            Post::class => null,
            Comment::class => null,
        ];

        $expected = array_combine(
            array_keys($entities),
            ['post', 'comment']
        );
        ksort($expected);

        $configuration = $this->createAuditConfiguration([
            'entities' => $entities,
        ]);

        $reader = $this->getReader($configuration);

        $this->assertSame($expected, $reader->getEntities(), 'entities are sorted.');
    }

    public function testGetAudits(): void
    {
        $reader = $this->getReader($this->getAuditConfiguration());

        /** @var AuditEntry[] $audits */
        $audits = $reader->getAudits(Author::class, null, 1, 50);

        $this->assertCount(5, $audits, 'result count is ok.');
        $this->assertSame(AuditReader::REMOVE, $audits[0]->getType(), 'entry1 is a remove operation.');
        $this->assertSame(AuditReader::UPDATE, $audits[1]->getType(), 'entry2 is an update operation.');
        $this->assertSame(AuditReader::INSERT, $audits[2]->getType(), 'entry3 is an insert operation.');
        $this->assertSame(AuditReader::INSERT, $audits[3]->getType(), 'entry4 is an insert operation.');
        $this->assertSame(AuditReader::INSERT, $audits[4]->getType(), 'entry5 is an insert operation.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->getAudits(Post::class, null, 1, 50);

        $this->assertCount(11, $audits, 'result count is ok.');
        $this->assertSame(AuditReader::DISSOCIATE, $audits[0]->getType(), 'entry1 is a dissociate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[1]->getType(), 'entry2 is an associate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[2]->getType(), 'entry3 is an associate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[3]->getType(), 'entry4 is an associate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[4]->getType(), 'entry5 is an associate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[5]->getType(), 'entry6 is an associate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[6]->getType(), 'entry7 is an associate operation.');
        $this->assertSame(AuditReader::INSERT, $audits[7]->getType(), 'entry8 is an insert operation.');
        $this->assertSame(AuditReader::INSERT, $audits[8]->getType(), 'entry9 is an insert operation.');
        $this->assertSame(AuditReader::INSERT, $audits[9]->getType(), 'entry10 is an insert operation.');
        $this->assertSame(AuditReader::INSERT, $audits[10]->getType(), 'entry11 is an insert operation.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->getAudits(Comment::class, null, 1, 50);

        $this->assertCount(3, $audits, 'result count is ok.');
        $this->assertSame(AuditReader::INSERT, $audits[0]->getType(), 'entry1 is an insert operation.');
        $this->assertSame(AuditReader::INSERT, $audits[1]->getType(), 'entry2 is an insert operation.');
        $this->assertSame(AuditReader::INSERT, $audits[2]->getType(), 'entry3 is an insert operation.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->getAudits(Tag::class, null, 1, 50);

        $this->assertCount(12, $audits, 'result count is ok.');
        $this->assertSame(AuditReader::DISSOCIATE, $audits[0]->getType(), 'entry1 is a dissociate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[1]->getType(), 'entry2 is an associate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[2]->getType(), 'entry3 is an associate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[3]->getType(), 'entry4 is an associate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[4]->getType(), 'entry5 is an associate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[5]->getType(), 'entry6 is an associate operation.');
        $this->assertSame(AuditReader::ASSOCIATE, $audits[6]->getType(), 'entry7 is an associate operation.');
        $this->assertSame(AuditReader::INSERT, $audits[7]->getType(), 'entry8 is an insert operation.');
        $this->assertSame(AuditReader::INSERT, $audits[8]->getType(), 'entry9 is an insert operation.');
        $this->assertSame(AuditReader::INSERT, $audits[9]->getType(), 'entry10 is an insert operation.');
        $this->assertSame(AuditReader::INSERT, $audits[10]->getType(), 'entry11 is an insert operation.');
        $this->assertSame(AuditReader::INSERT, $audits[11]->getType(), 'entry12 is an insert operation.');

        $this->expectException(\InvalidArgumentException::class);
        $reader->getAudits(Post::class, null, 0, 50);
        $reader->getAudits(Post::class, null, -1, 50);
    }

    /**
     * @depends testGetAudits
     */
    public function testGetAuditsHonorsId(): void
    {
        $reader = $this->getReader($this->getAuditConfiguration());

        /** @var AuditEntry[] $audits */
        $audits = $reader->getAudits(Author::class, 1, 1, 50);

        $this->assertCount(2, $audits, 'result count is ok.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->getAudits(Post::class, 1, 1, 50);

        $this->assertCount(3, $audits, 'result count is ok.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->getAudits(Comment::class, 1, 1, 50);

        $this->assertCount(1, $audits, 'result count is ok.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->getAudits(Post::class, 0, 1, 50);
        $this->assertSame([], $audits, 'no result when id is invalid.');
    }

    /**
     * @depends testGetAudits
     */
    public function testGetAuditsHonorsPageSize(): void
    {
        $reader = $this->getReader($this->getAuditConfiguration());

        /** @var AuditEntry[] $audits */
        $audits = $reader->getAudits(Author::class, null, 1, 2);

        $this->assertCount(2, $audits, 'result count is ok.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->getAudits(Author::class, null, 2, 2);

        $this->assertCount(2, $audits, 'result count is ok.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->getAudits(Author::class, null, 3, 2);

        $this->assertCount(1, $audits, 'result count is ok.');

        $this->expectException(\InvalidArgumentException::class);
        $reader->getAudits(Post::class, null, 1, 0);
        $reader->getAudits(Post::class, null, 1, -1);
    }

    /**
     * @depends testGetAudits
     */
    public function testGetAuditsHonorsFilter(): void
    {
        $reader = $this->getReader($this->getAuditConfiguration());

        /** @var AuditEntry[] $audits */
        $audits = $reader->filterBy(AuditReader::UPDATE)->getAudits(Author::class, null, 1, 50);

        $this->assertCount(1, $audits, 'result count is ok.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->filterBy(AuditReader::INSERT)->getAudits(Author::class, null, 1, 50);

        $this->assertCount(3, $audits, 'result count is ok.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->filterBy(AuditReader::REMOVE)->getAudits(Author::class, null, 1, 50);

        $this->assertCount(1, $audits, 'result count is ok.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->filterBy(AuditReader::ASSOCIATE)->getAudits(Author::class, null, 1, 50);

        $this->assertCount(0, $audits, 'result count is ok.');

        /** @var AuditEntry[] $audits */
        $audits = $reader->filterBy(AuditReader::DISSOCIATE)->getAudits(Author::class, null, 1, 50);

        $this->assertCount(0, $audits, 'result count is ok.');
    }

    /**
     * @depends testGetAudits
     */
    public function testGetAudit(): void
    {
        $reader = $this->getReader($this->getAuditConfiguration());

        $audits = $reader->getAudit(Author::class, 1);

        $this->assertCount(1, $audits, 'result count is ok.');
    }

    /**
     * @depends testGetAudits
     */
    public function testGetAuditHonorsFilter(): void
    {
        $reader = $this->getReader($this->getAuditConfiguration());

        $audits = $reader->filterBy(AuditReader::UPDATE)->getAudit(Author::class, 1);

        $this->assertCount(0, $audits, 'result count is ok.');
    }

    protected function getReader(AuditConfiguration $configuration = null): AuditReader
    {
        return new AuditReader($configuration ?? $this->createAuditConfiguration(), $this->getEntityManager());
    }
}
