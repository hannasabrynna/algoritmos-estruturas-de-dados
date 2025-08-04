<?php

namespace App\DataStructures\BPlusTree;

class BPlusTree
{
    private BPlusNode $root;
    private int $maxKeys;

    public function __construct(int $maxKeys = 4)
    {
        $this->root = new BPlusNode(true); // começa com uma folha
        $this->maxKeys = $maxKeys;
    }

    public function getRoot(): BPlusNode
    {
        return $this->root;
    }

    public function insert($key, $value)
{
    $leaf = $this->findLeaf($key);

    $this->insertIntoLeaf($leaf, $key, $value);

    if ($leaf->isFull($this->maxKeys)) {
        $this->splitLeaf($leaf);
    }
}

private function findLeaf($key): BPlusNode
{
    $node = $this->root;
    while (!$node->isLeaf) {
        for ($i = 0; $i < count($node->keys); $i++) {
            if ($key < $node->keys[$i]) {
                $node = $node->children[$i];
                continue 2;
            }
        }
        $node = end($node->children);
    }
    return $node;
}

private function insertIntoLeaf(BPlusNode $leaf, $key, $value): void
{
    $data = ['key' => $key, 'value' => $value];

    $inserted = false;
    for ($i = 0; $i < count($leaf->keys); $i++) {
        if ($key < $leaf->keys[$i]) {
            array_splice($leaf->keys, $i, 0, [$key]);
            array_splice($leaf->children, $i, 0, [$data]);
            $inserted = true;
            break;
        }
    }

    if (!$inserted) {
        $leaf->keys[] = $key;
        $leaf->children[] = $data;
    }
}

private function splitLeaf(BPlusNode $leaf): void
{
    $mid = ceil($this->maxKeys / 2);

    $newLeaf = new BPlusNode(true);
    $newLeaf->parent = $leaf->parent;

    $newLeaf->keys = array_splice($leaf->keys, $mid);
    $newLeaf->children = array_splice($leaf->children, $mid);

    // Encadeamento entre folhas
    $newLeaf->nextLeaf = $leaf->nextLeaf;
    $leaf->nextLeaf = $newLeaf;

    $promotedKey = $newLeaf->keys[0];

    if ($leaf === $this->root) {
        $newRoot = new BPlusNode(false);
        $newRoot->keys = [$promotedKey];
        $newRoot->children = [$leaf, $newLeaf];
        $this->root = $newRoot;

        $leaf->parent = $newRoot;
        $newLeaf->parent = $newRoot;
    } else {
        $this->insertIntoParent($leaf, $promotedKey, $newLeaf);
    }
}

private function insertIntoParent(BPlusNode $leftNode, $key, BPlusNode $rightNode): void
{
    $parent = $leftNode->parent;

    // Encontrar posição onde a chave deve ser inserida
    $index = 0;
    while ($index < count($parent->children) && $parent->children[$index] !== $leftNode) {
        $index++;
    }

    array_splice($parent->keys, $index, 0, [$key]);
    array_splice($parent->children, $index + 1, 0, [$rightNode]);
    $rightNode->parent = $parent;

    if ($parent->isFull($this->maxKeys)) {
        $this->splitInternalNode($parent);
    }
}

private function splitInternalNode(BPlusNode $node): void
{
    $midIndex = intval($this->maxKeys / 2);
    $promotedKey = $node->keys[$midIndex];

    $newNode = new BPlusNode(false);
    $newNode->keys = array_splice($node->keys, $midIndex + 1);
    $newNode->children = array_splice($node->children, $midIndex + 1);

    foreach ($newNode->children as $child) {
        $child->parent = $newNode;
    }

    if ($node === $this->root) {
        $newRoot = new BPlusNode(false);
        $newRoot->keys = [$promotedKey];
        $newRoot->children = [$node, $newNode];

        $node->parent = $newRoot;
        $newNode->parent = $newRoot;

        $this->root = $newRoot;
    } else {
        $this->insertIntoParent($node, $promotedKey, $newNode);
    }
}

public function getLeftmostLeaf(): ?BPlusNode
{
    $node = $this->root;
    while (!$node->isLeaf) {
        $node = $node->children[0];
    }
    return $node;
}

// public function getAllRecords(): array
// {
//     $records = [];
//     $this->traverseLeafNodes(function($key, $value) use (&$records) {
//         $records[$key] = $value;
//     });
//     return $records;
// }
    public function getAll(): array
    {
        $records = [];

        $node = $this->getLeftmostLeaf();
        while ($node !== null) {
            foreach ($node->children as $entry) {
                $records[] = $entry['value']; // apenas os dados
            }
            $node = $node->nextLeaf;
        }

        return $records;
    }


private function traverseLeafNodes(callable $callback): void
{
    $node = $this->root;
    while (!$node->isLeaf()) {
        $node = $node->getChildren()[0];
    }

    while ($node) {
        foreach ($node->getKeys() as $i => $key) {
            $callback($key, $node->getValues()[$i]);
        }
        $node = $node->getNext(); // se estiver implementando ponteiro entre folhas
    }
}
}
