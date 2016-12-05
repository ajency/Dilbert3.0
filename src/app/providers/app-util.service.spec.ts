/* tslint:disable:no-unused-variable */

import { TestBed, async, inject } from '@angular/core/testing';
import { AppUtilService } from './app-util.service';

describe('Service: AppUtil', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [AppUtilService]
    });
  });

  it('should ...', inject([AppUtilService], (service: AppUtilService) => {
    expect(service).toBeTruthy();
  }));
});
