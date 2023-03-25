<?php
$playwright = <<<EOL
    import { test, expect } from '@playwright/test';
    import { faker } from '@faker-js/faker';
    import { baseeurl } from '../constantes';
    
    test('GET $tablename', async ({ request }) => {
      const res = await request.get(baseurl+`/api/get/$tablename`, {
        data: { }
      });
      let json = await res.json();
      expect(res.ok()).toBeTruthy();
    });
    
    test('GET ONE $tablename', async ({ request }) => {
      const res = await request.get(baseurl+`/api/get/$tablename/1`, {
        data: { }
      });
      let json = await res.json();
      expect(res.ok()).toBeTruthy();
    });
    
    test('Post $tablename', async ({ request }) => {
      const res = await request.post(baseurl+`/api/post/$tablename`, {
        data: {
          $paramsJson
        }
      });
      let json = await res.json();
      expect(res.ok()).toBeTruthy();
    });
    
    test('Post $tablename', async ({ request }) => {
      const res = await request.post(baseurl+`/api/search/$tablename`, {
        data: {
          'search' : [{key: 'id', operator:'=', value: 1}]
        }
      });
      let json = await res.json();
      expect(res.ok()).toBeTruthy();
    });
    
    test('Put $tablename', async ({ request }) => {
      const res = await request.put(baseurl+`/api/put/$tablename`, {
        data: {
          $paramsJson, id: 1
        }
      });
      let json = await res.json();
      expect(res.ok()).toBeTruthy();
    });
    
    test('Delete $tablename', async ({ request }) => {
      const res = await request.delete(baseurl+`/api/delete/$tablename/1`, {
        data: {}
      });
      let json = await res.json();
      expect(res.ok()).toBeTruthy();
    });
EOL;

file_put_contents("./files/{$name}/{$snake}Test.mjs", $playwright);
file_put_contents("./files/_TEST/{$snake}Test.mjs", $playwright);
